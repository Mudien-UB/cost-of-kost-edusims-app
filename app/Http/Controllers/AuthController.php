<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        return BaseResource::respond(
            Response::HTTP_OK,
            'Login successful',
            $this->authService->login($validatedData)
        );
    }

    public function logout()
    {
        $this->authService->logout();
        return BaseResource::respond(Response::HTTP_OK, 'Logout successful');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        return BaseResource::respond(
            Response::HTTP_OK,
            'Registration successful',
            $this->authService->register($validatedData)
        );
    }

}
