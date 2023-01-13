<?php

declare(strict_types=1);

namespace UI\Api\Responses\Account;

use Illuminate\Http\JsonResponse;
use UI\Api\Responses\ApiResponse;

class RegisterResponse extends ApiResponse
{
    public function __construct()
    {
    }

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}
