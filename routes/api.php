<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceHistoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')->controller(UserController::class)->group(function () {
    Route::get('/', 'getPaginatedUsers'); // GET /api/users?username=...&page=...&per_page=...
    Route::get('/{id}', 'getUserById');   // GET /api/users/{id}
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');       // POST /api/auth/login
    Route::post('/register', 'register'); // POST /api/auth/register
});

Route::middleware(['jwtToken', 'auth:api'])->group(function () {



    Route::prefix('/users')->group(function () {
        Route::put('/update-username', [UserController::class,'updateUsername']); // PATCH /api/users/username
        Route::post('/logout', [AuthController::class, 'logout']);          // POST /api/users/logout
    Route::get('/me', [UserController::class, 'getAuthenticatedUser']); // GET /api/users/me
    });

    Route::prefix('/finance')->group(function () {
        
        Route::prefix('/income')->controller(IncomeController::class)->group(function () {
            Route::post('/add', 'store'); // POST /api/finance/income/add
            Route::get('/{id}', 'show');  // GET /api/finance/income/{id}
        });

        Route::prefix('/expense')->controller(ExpenseController::class)->group(function () {
            Route::post('/add', 'store'); // POST /api/finance/expense/add
            Route::get('/{id}', 'show');  // GET /api/finance/expense/{id}
        });

        Route::get('/history', [FinanceHistoryController::class, 'index']); // GET /api/finance/history?type={type}
    });

});
