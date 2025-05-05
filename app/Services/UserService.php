<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    public function getUserById(string $id): UserResource
    {
        return new UserResource(User::findOrFail($id));
    }

    public function getPaginatedUsers(?string $username = null, int $page = 1, int $perPage = 10)
    {
        $query = User::query();

        if ($username) {
            $query->where('username', 'like', '%' . $username . '%');
        }

        $users = $query->orderByDesc('created_at')
                       ->paginate($perPage, ['*'], 'page', $page);

        if($users->isEmpty()) {
            abort(404, 'No users found');
        }

        return UserResource::collection($users);
    }

    public function updateUsername(string $username): UserResource
    {
        $user = auth()->user();

        if (User::where('username', $username)->exists()) {
            abort(400, 'Username already exists');
        }

        $user->username = $username;
        $user->save();

        return new UserResource($user);
    }

    public function updateUser(int $id, array $data): UserResource
    {
        $user = User::findOrFail($id);

        if (!empty($data['username']) && $data['username'] !== $user->username) {
            if (User::where('username', $data['username'])->where('id', '!=', $id)->exists()) {
                abort(400, 'Username already exists');
            }
            $user->username = $data['username'];
        }

        if (!empty($data['email']) && $data['email'] !== $user->email) {
            if (User::where('email', $data['email'])->where('id', '!=', $id)->exists()) {
                abort(400, 'Email already exists');
            }
            $user->email = $data['email'];
        }

        $user->save();

        return new UserResource($user);
    }

    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
