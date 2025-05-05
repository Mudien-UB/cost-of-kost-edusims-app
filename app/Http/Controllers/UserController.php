<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUserById(string $id)
{
 
    return BaseResource::respond(
        Response::HTTP_OK,
        'User retrieved successfully',
        $this->userService->getUserById($id)
    );
}


    public function getPaginatedUsers(Request $request)
    {
        $validated = $request->validate([
            'username'  => 'nullable|string|max:255',
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:1|max:100',
        ]);

        return BaseResource::respond(
            Response::HTTP_OK,
            'Users retrieved successfully',
            $this->userService->getPaginatedUsers(
                $validated['username'] ?? null,
                $validated['page'] ?? 1,
                $validated['per_page'] ?? 10
            )
        );
    }

    public function updateUsername(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
        ]);

        return BaseResource::respond(
            Response::HTTP_OK,
            'Username updated successfully',
            $this->userService->updateUsername($validated['username'])
        );
    }

    public function updateUser(Request $request, int $id)
    {
        $validated = $request->validate([
            'username' => 'nullable|string|max:255',
            'email'    => 'nullable|string|email|max:255',
        ]);

        return BaseResource::respond(
            Response::HTTP_OK,
            'User updated successfully',
            $this->userService->updateUser($id, $validated)
        );
    }

    public function deleteUser(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $this->userService->deleteUser($validated['id']);

        return BaseResource::respond(
            Response::HTTP_OK,
            'User deleted successfully'
        );
    }

    public function getAuthenticatedUser()
    {
        return BaseResource::respond(
            Response::HTTP_OK,
            'Authenticated user retrieved successfully',
            new UserResource(auth()->user())
        );
    }
}
