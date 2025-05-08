<?php

namespace App\Services;

use App\Models\Enums\CategoryType;
use App\Models\Income;
use Illuminate\Support\Carbon;

class IncomeService
{

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    public function create(int $amount,string $source,string $categoryName,string $date,string $note = null){

        $user = auth()->user();
        if(!$user){
            abort(402, "unauthorized");
        }

        $category = $this->categoryService->checkCategory($categoryName, CategoryType::INCOME);

        if(!$category){
            abort(404, "Category not found");
        }

        $expense = Income::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => $amount,
            'source' => $source,
            'date' => $date ? Carbon::parse($date) : now(),
            'note' => $note,
        ]);


        return $expense;
    }

    public function getIncomes($category = null, $date = null, $limit = 10, $page = 1, $order = 'desc'){
        $user = auth()->user();
        if(!$user){
            abort(401, "unauthorized");
        }

        $date = $date ?? now()->toDateString();

        $query = $user->incomes()->whereDate('date',$date);
        if ($category) {
            $query->whereHas('category', function ($q) use ($category){
                $q->where('name',$category);
            });
        }

        $query->orderBy('created_at', $order);

        return $query->paginate($limit, ['*'], 'page', $page);

    }

    public function getIncomeById(int $id){
        return Income::findOrFail($id);
    }
}
