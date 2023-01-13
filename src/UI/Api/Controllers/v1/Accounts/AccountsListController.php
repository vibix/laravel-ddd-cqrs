<?php

declare(strict_types=1);

namespace UI\Api\Controllers\v1\Accounts;

use Contexts\Account\Application\Models\AccountView;
use Contexts\Account\Application\Queries\GetAccounts;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class AccountsListController extends Controller
{
    public function __invoke(GetAccounts $query): JsonResponse
    {
        $users = $query()->map(fn (AccountView $account) => [
            'accountId' => $account->getAccountId()->getValue(),
            'name' => $account->getName(),
            'email' => $account->getEmail()->getValue(),
            'createdAt' => $account->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedA' => $account->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ]);
        return response()->json($users);
    }
}
