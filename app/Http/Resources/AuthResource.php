<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    protected $token;

    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    public function toArray($request)
    {
        return [
            'user' => [
                'id'       => $this->id,
                'username' => $this->username,
            ],
            'access_token' => $this->token,
        ];
    }
}
