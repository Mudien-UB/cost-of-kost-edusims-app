<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Http\Resources\IncomeResource;
use App\Services\IncomeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncomeController extends Controller
{
    protected IncomeService $incomeService;

    public function __construct(IncomeService $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'source' => 'required|string',
            'category_name' => 'required|string',
            'date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $response = $this->incomeService->create(
            $validated['amount'],
            $validated['source'],
            $validated['category_name'],
            $validated['date'] ?? now(),
            $validated['note'] ?? null
        );

        return BaseResource::respond(
            Response::HTTP_CREATED,
            "data pengeluaran tersimpan",
            new IncomeResource($response)
        );


    }

    public function show($id){
        $validated = validator(['id' => $id], [
            'id' => 'required|int|min:1'
        ])->validate();

        return BaseResource::respond(
            Response::HTTP_OK,
            "data didapat",
            new IncomeResource($this->incomeService->getIncomeById($validated['id']))
        );
    }


}
