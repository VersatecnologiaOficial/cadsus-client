<?php

namespace Versatecnologia\Cadsus\Cache;

use Versatecnologia\Cadsus\Contracts\TokenStoreInterface;
use Versatecnologia\Cadsus\DTO\AccessToken;

final class InMemoryTokenStore implements TokenStoreInterface
{
    private array $tokens = [];
    public function get(string $scope): ?AccessToken
    {
        return $this->tokens[$scope] ?? null;
    }
    public function put(string $scope, AccessToken $token): void
    {
        $this->tokens[$scope] = $token;
    }
    public function forget(string $scope): void
    {
        unset($this->tokens[$scope]);
    }
}
