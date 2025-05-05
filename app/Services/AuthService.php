<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function login($credentials)
    {
        $accessToken = auth()->attempt($credentials);
        if (!$accessToken) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
        }

        return new AuthResource(auth()->user(), $accessToken);
    }

    public function logout()
    {
        auth()->logout();
    }

    public function register(array $data)
    {
        $user = User::create([
            'name'=> $data['name'],
            'username'=> $data['username'],
            'email'=> $data['email'],
            'password'=> bcrypt($data['password']),
        ]);

        if (!$user) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Registration failed');
        }

        return new AuthResource($user, auth()->fromUser($user));
    }

    
}
