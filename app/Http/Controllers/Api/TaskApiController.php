<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TaskApiController extends Controller
{
    /**
     * Login dan generate token untuk API access
     * 
     * POST /api/login
     * Body: { "nim": "123456", "password": "secret" }
     * Response: { "token": "...", "user": {...} }
     */
    public function login(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nim', $request->nim)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nim' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate token untuk API access
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nim' => $user->nim,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    /**
     * Get authenticated user info
     * 
     * GET /api/user
     * Headers: Authorization: Bearer {token}
     * Response: { "id": 1, "nim": "...", "name": "..." }
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'nim' => $request->user()->nim,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ], 200);
    }

    /**
     * Get all tasks for authenticated user
     * 
     * GET /api/tasks
     * Headers: Authorization: Bearer {token}
     * Response: { "data": [{...}, {...}] }
     */
    public function index(Request $request)
    {
        $tasks = $request->user()
            ->tasks()
            ->latest()
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'completed' => (bool) $task->completed,
                    'created_at' => $task->created_at->toIso8601String(),
                    'updated_at' => $task->updated_at->toIso8601String(),
                ];
            });

        return response()->json([
            'data' => $tasks,
            'total' => $tasks->count(),
        ], 200);
    }

    /**
     * Get single task by ID
     * 
     * GET /api/tasks/{id}
     * Headers: Authorization: Bearer {token}
     * Response: { "data": {...} }
     */
    public function show(Request $request, Task $task)
    {
        // Ensure user owns this task
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'completed' => (bool) $task->completed,
                'created_at' => $task->created_at->toIso8601String(),
                'updated_at' => $task->updated_at->toIso8601String(),
            ]
        ], 200);
    }

    /**
     * Create new task
     * 
     * POST /api/tasks
     * Headers: Authorization: Bearer {token}
     * Body: { "title": "...", "description": "..." }
     * Response: { "data": {...}, "message": "..." }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'completed' => false,
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'completed' => (bool) $task->completed,
                'created_at' => $task->created_at->toIso8601String(),
                'updated_at' => $task->updated_at->toIso8601String(),
            ]
        ], 201);
    }

    /**
     * Update existing task
     * 
     * PUT /api/tasks/{id}
     * Headers: Authorization: Bearer {token}
     * Body: { "title": "...", "description": "...", "completed": true }
     * Response: { "data": {...}, "message": "..." }
     */
    public function update(Request $request, Task $task)
    {
        // Ensure user owns this task
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'completed' => (bool) $task->completed,
                'created_at' => $task->created_at->toIso8601String(),
                'updated_at' => $task->updated_at->toIso8601String(),
            ]
        ], 200);
    }

    /**
     * Toggle task completion status
     * 
     * POST /api/tasks/{id}/toggle
     * Headers: Authorization: Bearer {token}
     * Response: { "data": {...}, "message": "..." }
     */
    public function toggle(Request $request, Task $task)
    {
        // Ensure user owns this task
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $task->update([
            'completed' => !$task->completed,
        ]);

        return response()->json([
            'message' => 'Task toggled successfully',
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'completed' => (bool) $task->completed,
                'created_at' => $task->created_at->toIso8601String(),
                'updated_at' => $task->updated_at->toIso8601String(),
            ]
        ], 200);
    }

    /**
     * Delete task
     * 
     * DELETE /api/tasks/{id}
     * Headers: Authorization: Bearer {token}
     * Response: { "message": "..." }
     */
    public function destroy(Request $request, Task $task)
    {
        // Ensure user owns this task
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ], 200);
    }

    /**
     * Logout (revoke current token)
     * 
     * POST /api/logout
     * Headers: Authorization: Bearer {token}
     * Response: { "message": "..." }
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
