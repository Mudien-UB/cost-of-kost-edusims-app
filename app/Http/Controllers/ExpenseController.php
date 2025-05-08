<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Http\Resources\ExpenseResource;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'reason' => 'required|string',
            'category_name' => 'required|string',
            'date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $response = $this->expenseService->create(
            $validated['amount'],
            $validated['reason'],
            $validated['category_name'],
            $validated['date'] ?? now(),
            $validated['note'] ?? null
        );

        return BaseResource::respond(
            Response::HTTP_CREATED,
            "data pengeluaran tersimpan",
            new ExpenseResource($response)
        );


    }

    public function show($id){
        $validated = validator(['id' => $id], [
            'id' => 'required|int|min:1'
        ])->validate();

        return BaseResource::respond(
            Response::HTTP_OK,
            "data didapat",
            new ExpenseResource($this->expenseService->getExpenseById($validated['id']))
        );
    }


}
