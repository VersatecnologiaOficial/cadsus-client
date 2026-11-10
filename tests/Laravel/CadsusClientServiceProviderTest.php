<?php

namespace Versatecnologia\Cadsus\Tests\Laravel;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Versatecnologia\Cadsus\CadsusClient;
use Versatecnologia\Cadsus\Contracts\CadsusClientInterface;
use Versatecnologia\Cadsus\CadsusClientServiceProvider;

final class CadsusClientServiceProviderTest extends TestCase
{
    private function bootApplication(array $config = []): Application
    {
        $app = new Application(sys_get_temp_dir());
        $app->instance('config', new Repository($config));
        $app->register(CadsusClientServiceProvider::class);
        $app->boot();

        return $app;
    }

    public function testMergesPackageConfigWithoutOverridingApplicationValues(): void
    {
        $app = $this->bootApplication(['cadsus' => ['environment' => 'homologation']]);

        self::assertSame('homologation', $app['config']->get('cadsus.environment'));
        self::assertSame(60, $app['config']->get('cadsus.token_safety_margin_seconds'));
        self::assertFalse($app['config']->get('cadsus.soap_trace'));
    }

    public function testBindsClientAsSingletonForConcreteAndInterface(): void
    {
        $app = $this->bootApplication();

        $client = $app->make(CadsusClient::class);

        self::assertInstanceOf(CadsusClient::class, $client);
        self::assertSame($client, $app->make(CadsusClient::class));
        self::assertSame($client, $app->make(CadsusClientInterface::class));
    }

    public function testPublishesConfigUnderCadsusClientConfigTag(): void
    {
        $app = $this->bootApplication();

        $paths = ServiceProvider::pathsToPublish(CadsusClientServiceProvider::class, 'cadsus-client-config');

        self::assertCount(1, $paths);
        self::assertSame(realpath(dirname(__DIR__, 2) . '/config/cadsus.php'), realpath(array_key_first($paths)));
        self::assertSame($app->configPath('cadsus.php'), reset($paths));
    }
}
