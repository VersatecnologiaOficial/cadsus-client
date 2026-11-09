<?php

declare(strict_types=1);

namespace Versatecnologia\Cadsus\Tests\Contract;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Versatecnologia\Cadsus\DTO\AccessToken;
use Versatecnologia\Cadsus\Environment\CadsusEnvironment;
use Versatecnologia\Cadsus\Soap\SoapClientFactory;

final class SoapClientFactoryTest extends TestCase
{
    public function testItCreatesAClientWithoutMakingANetworkRequest(): void
    {
        $client = (new SoapClientFactory())->create(
            CadsusEnvironment::HOMOLOGATION,
            new AccessToken('not-a-real-token', new DateTimeImmutable('+30 minutes')),
        );

        self::assertStringContainsString('PDQSupplier_PRPA_IN201305UV02', implode("\n", $client->__getFunctions()));
    }
}
