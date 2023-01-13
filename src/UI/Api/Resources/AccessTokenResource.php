<?php

declare(strict_types=1);

namespace UI\Api\Resources;

/**
 * @property string $resource
 */
class AccessTokenResource extends ApiResource
{
    public function __construct(string $accessToken)
    {
        parent::__construct($accessToken);
    }

    public function toArray($request): array
    {
        return [
            'token' => $this->resource
        ];
    }
}
