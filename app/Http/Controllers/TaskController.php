<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Simpan tugas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:Tinggi,Sedang,Rendah',
            'due_date'    => 'nullable|date',
        ]);

        Task::create([
            'user_id'     => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority'    => $validated['priority'],
            'due_date'    => $validated['due_date'] ?? null,
            'completed'   => false,
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Toggle selesai/belum.
     */
    public function toggle(Task $task)
    {
        // pastikan hanya pemilik tugas yang boleh ubah
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'completed' => ! $task->completed,
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Hapus tugas.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('dashboard');
    }

    /**
     * Tampilkan form edit tugas.
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Proses update tugas.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:Tinggi,Sedang,Rendah',
            'due_date'    => 'nullable|date',
        ]);

        $task->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority'    => $validated['priority'],
            'due_date'    => $validated['due_date'] ?? null,
        ]);

        return redirect()->route('dashboard');
    }
}
