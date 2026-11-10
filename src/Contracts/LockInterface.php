<?php

namespace Versatecnologia\Cadsus\Contracts;

interface LockInterface
{
    public function synchronized(string $scope, callable $callback): mixed;
}
