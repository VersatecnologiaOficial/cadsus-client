<?php

namespace Versatecnologia\Cadsus\Contracts;

use Versatecnologia\Cadsus\DTO\CadsusSearchRequest;
use Versatecnologia\Cadsus\DTO\CadsusSearchResult;

interface CredentialedCadsusClientInterface
{
    public function search(CadsusSearchRequest $request): CadsusSearchResult;
}
