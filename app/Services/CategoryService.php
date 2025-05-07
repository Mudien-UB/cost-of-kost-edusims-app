<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Enum\CategoryType;

class CategoryService
{
    /**
     * Mendapatkan semua kategori.
     */
    public function getAll()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            abort(404, 'No categories found');
        }

        return $categories;
    }

    /**
     * Mendapatkan kategori dengan pagination berdasarkan nama dan tipe.
     */
    public function getAllPaginated($name, $type = 'expense', $perPage = 10, $page = 1)
    {
        $query = Category::query();

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        if (in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        $categories = $query->orderByDesc('created_at')->paginate($perPage, ['*'], 'page', $page);

        if ($categories->isEmpty()) {
            abort(404, 'No categories found');
        }

        return $categories;
    }

    /**
     * Mendapatkan kategori berdasarkan ID.
     */
    public function getById($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Membuat kategori baru.
     */
    public function create($data)
    {
        if (Category::where('name', $data['name'])->exists()) {
            abort(409, 'Category already exists');
        }

        if (!CategoryType::tryFrom($data['type'])) {
            abort(400, 'Invalid category type');
        }

        $category = Category::create($data);
        return $category;
    }

    /**
     * Memperbarui kategori berdasarkan ID.
     */
    public function update($id, $data)
    {
        $category = Category::findOrFail($id);

        if (isset($data['type']) && !CategoryType::tryFrom($data['type'])) {
            abort(400, 'Invalid category type');
        }

        $category->update($data);
        return $category;
    }

    /**
     * Menghapus kategori berdasarkan ID.
     */
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
