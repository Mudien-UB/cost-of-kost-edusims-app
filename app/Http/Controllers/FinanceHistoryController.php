<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResourcePageable;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\IncomeResource;
use App\Models\Enums\CategoryType;
use App\Services\ExpenseService;
use App\Services\IncomeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinanceHistoryController extends Controller
{
    protected IncomeService $incomeService;
    protected ExpenseService $expenseService;

    public function __construct(IncomeService $incomeService, ExpenseService $expenseService)
    {
        $this->incomeService = $incomeService;
        $this->expenseService = $expenseService;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string',
            'date'     => 'nullable|date',
            'limit'    => 'nullable|integer|min:1',
            'page'     => 'nullable|integer|min:1',
            'order'    => 'nullable|in:asc,desc',
            'type'     => 'required|in:INCOME,EXPENSE,income,expense',
        ]);

        $type = strtoupper($validated['type']);
        $category = $validated['category'] ?? null;
        $date = $validated['date'] ?? null;
        $limit = $validated['limit'] ?? 10;
        $page = $validated['page'] ?? 1;
        $order = $validated['order'] ?? 'desc';

        \Log::info('Tipe:', ['type' => $type]);
        \Log::info('Enum INCOME:', ['value' => CategoryType::INCOME->value]);
        \Log::info('Enum EXPENSE:', ['value' => CategoryType::EXPENSE->value]);

        return match ($type) {
            CategoryType::INCOME->value => $this->buildResponse(
                $this->incomeService->getIncomes($category, $date, $limit, $page, $order),
                IncomeResource::class,
                'Data Pemasukan didapat'
            ),

            CategoryType::EXPENSE->value => $this->buildResponse(
                $this->expenseService->getExpenses($category, $date, $limit, $page, $order),
                ExpenseResource::class,
                'Data Pengeluaran didapat'
            ),

            default => abort(Response::HTTP_BAD_REQUEST, 'Type tidak tersedia'),
        };
    }

    private function buildResponse($data, string $resourceClass, string $message)
    {
        return BaseResourcePageable::respond(
            Response::HTTP_OK,
            $message,
            $resourceClass::collection($data)
        );
    }
}
