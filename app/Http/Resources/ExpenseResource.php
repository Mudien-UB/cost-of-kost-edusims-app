<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'note' => $this->note,
            'date' => $this->date,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
        ];
    }
}
