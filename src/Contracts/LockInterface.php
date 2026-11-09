<?php
declare(strict_types=1);
namespace Versatecnologia\Cadsus\Contracts;
interface LockInterface
{
    public function synchronized(string $scope, callable $callback): mixed;
}
