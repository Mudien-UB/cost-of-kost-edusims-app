<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    /**
     * Mendapatkan data user berdasarkan id.
     *
     * @param string $id ID pengguna yang akan dicari.
     * @return UserResource Data pengguna dalam format resource.
     */
    public function getUserById(string $id): UserResource
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Mendapatkan daftar pengguna yang dipaginasi, dengan filter opsional berdasarkan username.
     *
     */
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

    /**
     * Memperbarui username pengguna yang sedang login.
     *
     */
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

    /**
     * Memperbarui data pengguna berdasarkan id.
     *
     */
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

    /**
     * Menghapus pengguna berdasarkan id.
     *
     */
    public function deleteUser(int $id): bool
    {
        // Mencari pengguna berdasarkan ID dan menghapusnya
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
