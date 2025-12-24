<?php

/*
===============================================================================
🌐 WEB SERVICE / REST API ROUTES
===============================================================================
File ini berisi semua endpoint REST API untuk ReminderApps.
Menggunakan Laravel Sanctum untuk token-based authentication.

Base URL: /api
Authentication: Bearer Token (dari POST /api/login)
Format: JSON Request & Response

Dokumentasi lengkap: lihat API_DOCUMENTATION.md
===============================================================================
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
===============================================================================
🔐 AUTENTIFIKASI - API Authentication (Web Service)
===============================================================================
Endpoint untuk login dan mendapatkan Bearer Token untuk akses API.
Token ini digunakan untuk semua request ke protected endpoints.
===============================================================================
*/

// API Authentication - Login untuk dapat token
Route::post('/login', [TaskApiController::class, 'login'])->name('api.login');

/*
===============================================================================
🌐 WEB SERVICE - Protected API Endpoints
===============================================================================
Semua endpoint di bawah ini memerlukan Bearer Token di header:
Authorization: Bearer {token}

Menggunakan middleware 'auth:sanctum' untuk validasi token.
===============================================================================
*/

// Protected API Routes - butuh authentication
Route::middleware('auth:sanctum')->group(function () {
    
    // User info
    Route::get('/user', [TaskApiController::class, 'user'])->name('api.user');
    
    // Tasks CRUD
    Route::get('/tasks', [TaskApiController::class, 'index'])->name('api.tasks.index');
    Route::post('/tasks', [TaskApiController::class, 'store'])->name('api.tasks.store');
    Route::get('/tasks/{task}', [TaskApiController::class, 'show'])->name('api.tasks.show');
    Route::put('/tasks/{task}', [TaskApiController::class, 'update'])->name('api.tasks.update');
    Route::delete('/tasks/{task}', [TaskApiController::class, 'destroy'])->name('api.tasks.destroy');
    Route::post('/tasks/{task}/toggle', [TaskApiController::class, 'toggle'])->name('api.tasks.toggle');
    
    // Logout (revoke token)
    Route::post('/logout', [TaskApiController::class, 'logout'])->name('api.logout');
});
