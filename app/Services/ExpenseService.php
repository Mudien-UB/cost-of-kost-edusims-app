<?php

namespace App\Services;

use App\Models\Enums\CategoryType;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class ExpenseService {

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    public function create(int $amount,string $reason,string $categoryName,string $date,string $note = null){

        $user = auth()->user();
        if(!$user){
            abort(402, "unauthorized");
        }

        $category = $this->categoryService->checkCategory($categoryName, CategoryType::EXPENSE);

        if(!$category){
            abort(404, "Category not found");
        }

        $expense = Expense::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => $amount,
            'reason' => $reason,
            'date' => $date ? Carbon::parse($date) : now(),
            'note' => $note,
        ]);


        return $expense;
    }

    public function getExpenses($category = null, $date = null, $limit = 10, $page = 1, $order = 'desc'){
        $user = auth()->user();
        if(!$user){
            abort(401, "unauthorized");
        }

        $date = $date ?? now()->toDateString();

        $query = $user->expenses()->whereDate('date',$date);
        if ($category) {
            $query->whereHas('category', function ($q) use ($category){
                $q->where('name',$category);
            });
        }

        $query->orderBy('created_at', $order);

        return $query->paginate($limit, ['*'], 'page', $page);

    }

    public function getExpenseById(int $id){
        return Expense::findOrFail($id);
    }
}
