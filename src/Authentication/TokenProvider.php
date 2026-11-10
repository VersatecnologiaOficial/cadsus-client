<?php

namespace Versatecnologia\Cadsus\Authentication;

use DateTimeImmutable;
use Versatecnologia\Cadsus\Contracts\LockInterface;
use Versatecnologia\Cadsus\Contracts\TokenStoreInterface;
use Versatecnologia\Cadsus\DTO\AccessToken;
use Versatecnologia\Cadsus\DTO\CadsusCredentials;

final readonly class TokenProvider
{
    public function __construct(private AuthenticationClient $auth, private TokenStoreInterface $store, private LockInterface $lock, private int $safetyMarginSeconds = 60) {}
    public function token(CadsusCredentials $credentials, bool $forceRefresh = false): AccessToken
    {
        $scope = $credentials->cacheScope();
        if (!$forceRefresh && ($token = $this->usable($scope))) {
            return $token;
        }
        return $this->lock->synchronized($scope, function () use ($credentials, $scope, $forceRefresh): AccessToken {
            if (!$forceRefresh && ($token = $this->usable($scope))) {
                return $token;
            }
            $token = $this->auth->authenticate($credentials);
            $this->store->put($scope, $token);
            return $token;
        });
    }
    public function invalidate(CadsusCredentials $credentials): void
    {
        $this->store->forget($credentials->cacheScope());
    }
    private function usable(string $scope): ?AccessToken
    {
        $token = $this->store->get($scope);
        return $token?->isUsableAt(new DateTimeImmutable(), $this->safetyMarginSeconds) ? $token : null;
    }
}
