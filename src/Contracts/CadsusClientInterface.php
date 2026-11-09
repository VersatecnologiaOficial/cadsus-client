<?php

declare(strict_types=1);

namespace Versatecnologia\Cadsus\Contracts;

use Versatecnologia\Cadsus\DTO\CadsusCredentials;

interface CadsusClientInterface
{
    public function forCredentials(CadsusCredentials $credentials): CredentialedCadsusClientInterface;
}
