<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('/users')->controller(UserController::class)->group(function () {
    Route::get('/', 'getPaginatedUsers');   // GET /api/users?username=...&page=...&per_page=...
    Route::get('/{id}', 'getUserById'); // GET /api/users/{id}
});


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login'); // POST /api/auth/login
    Route::post('/register', 'register'); // POST /api/auth/register
});

Route::middleware(['jwtToken', 'auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // POST /api/auth/logout
    Route::get('/me', [UserController::class, 'getAuthenticatedUser']); // GET /api/auth/me

    Route::prefix('/users')->controller(UserController::class)->group(function () {
        Route::put('/update-username', 'updateUsername'); // PATCH /api/users/username
    });
});
