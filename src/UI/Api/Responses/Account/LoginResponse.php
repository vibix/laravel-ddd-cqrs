<?php

declare(strict_types=1);

namespace UI\Api\Responses\Account;

use Illuminate\Http\JsonResponse;
use UI\Api\Resources\AccessTokenResource;
use UI\Api\Responses\ApiResponse;

class LoginResponse extends ApiResponse
{
    public function __construct(private readonly string $accessToken)
    {
    }

    public function toResponse($request): JsonResponse
    {
        return AccessTokenResource::make($this->accessToken)->toResponse($request);
    }
}
