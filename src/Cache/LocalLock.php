<?php

namespace Versatecnologia\Cadsus\Cache;

use Versatecnologia\Cadsus\Contracts\LockInterface;

final class LocalLock implements LockInterface
{
    public function synchronized(string $scope, callable $callback): mixed
    {
        return $callback();
    }
}
