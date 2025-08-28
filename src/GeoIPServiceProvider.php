<?php

namespace Torann\GeoIP;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Torann\GeoIP\Contracts\ServiceInterface;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    #[\Override]
    public function register(): void
    {
        $this->registerGeoIpService();

        if ($this->app->runningInConsole()) {
            $this->registerResources();
            $this->registerGeoIpCommands();
        }

        if ($this->isLumen() === false) {
            $this->mergeConfigFrom(__DIR__ . '/../config/geoip.php', 'geoip');
        }
    }

    /**
     * Register currency provider.
     *
     * @return void
     */
    public function registerGeoIpService(): void
    {
        $this->app->singleton('geoip', fn($app) => new GeoIP(
            $app->config->get('geoip', []),
            $app['cache']
        ));
    }

    /**
     * Register resources.
     *
     * @return void
     */
    public function registerResources(): void
    {
        if ($this->isLumen() === false) {
            $this->publishes([
                __DIR__ . '/../config/geoip.php' => config_path('geoip.php'),
            ], 'config');
        }
    }

    /**
     * Register commands.
     *
     * @return void
     */
    public function registerGeoIpCommands(): void
    {
        $this->commands([
            Console\Update::class,
            Console\Clear::class,
        ]);
    }

    /**
     * Check if package is running under Lumen app
     *
     * @return bool
     */
    protected function isLumen(): bool
    {
        return Str::contains($this->app->version(), 'Lumen') === true;
    }
}
