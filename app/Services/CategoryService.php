<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Enums\CategoryType;

class CategoryService
{
    /**
     * Mendapatkan kategori dengan pagination berdasarkan nama dan tipe.
     */
    public function getCategories(string $name, CategoryType $type, int $limit = 5, int $page = 1)
    {
        $query = Category::query();

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        $query->where('type', $type->value);

        return $query->orderByDesc('created_at')->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Membuat kategori baru.
     */
    public function addCategory(string $name, CategoryType $type)
    {
        return Category::create([
            'name' => $name,
            'type' => $type->value,
        ]);
    }

    /**
     * Menghapus kategori berdasarkan ID.
     */
    public function delete(string $id)
    {
        $category = Category::findOrFail($id);
        return $category->delete();
    }

    /**
     * Mengecek apakah kategori sudah ada, jika belum maka akan dibuat.
     */
    public function checkCategory(string $categoryName, CategoryType $type)
    {
        $category = Category::where('name', 'like', $categoryName)
                            ->where('type', $type->value)
                            ->first();

        if (!$category) {
            $category = $this->addCategory($categoryName, $type);
        }

        return $category;
    }
}
