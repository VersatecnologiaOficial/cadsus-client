<?php

namespace Versatecnologia\Cadsus;

use Illuminate\Support\ServiceProvider;
use Versatecnologia\Cadsus\Contracts\CadsusClientInterface;

class CadsusClientServiceProvider extends ServiceProvider
{
    private const CONFIG_PATH = __DIR__ . '/../config/cadsus.php';

    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'cadsus');

        $this->app->singleton(CadsusClient::class, static fn() => CadsusClient::create());
        $this->app->alias(CadsusClient::class, CadsusClientInterface::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => $this->app->configPath('cadsus.php'),
            ], 'cadsus-client-config');
        }
    }
}
