<?php

declare(strict_types=1);

namespace Versatecnologia\Cadsus\Tests\Unit;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Versatecnologia\Cadsus\Authentication\AuthenticationClient;
use Versatecnologia\Cadsus\Authentication\TokenProvider;
use Versatecnologia\Cadsus\Cache\InMemoryTokenStore;
use Versatecnologia\Cadsus\Cache\LocalLock;
use Versatecnologia\Cadsus\DTO\AccessToken;
use Versatecnologia\Cadsus\DTO\CadsusCredentials;

final class TokenProviderTest extends TestCase
{
    public function testItReusesAValidCachedToken(): void
    {
        $credentials = new CadsusCredentials('tenant', 'pem');

        $store = new InMemoryTokenStore();

        $expected = new AccessToken('cached', new DateTimeImmutable('+30 minutes'));

        $store->put($credentials->cacheScope(), $expected);

        $auth = new AuthenticationClient(new Client(['handler' => HandlerStack::create(new MockHandler([]))]));

        self::assertSame($expected, (new TokenProvider($auth, $store, new LocalLock()))->token($credentials));
    }
}
