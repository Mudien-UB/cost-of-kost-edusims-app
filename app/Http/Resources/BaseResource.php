<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class BaseResource extends JsonResource
{
    protected string $message;
    protected int $statusCode;

    public function __construct($resource, string $message = 'Success', int $statusCode = 200)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->resource,
            'timestamps'=> now()->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Static response helper.
     */
    public static function respond(int $status, string $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], $status);
    }
}
