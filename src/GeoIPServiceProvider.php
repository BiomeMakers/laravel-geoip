<?php

namespace Torann\GeoIP;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use RuntimeException;
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
        $this->registerServiceInterfaceBinding();

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
            $app['cache'],
            $app->make(ServiceInterface::class)
        ));
    }

    /**
     * Bind ServiceInterface to a concrete implementation according to config.
     */
    protected function registerServiceInterfaceBinding(): void
    {
        $this->app->singleton(ServiceInterface::class, function ($app) {
            $config = $app['config']->get('geoip', []);

            if (!empty($config['service']) && class_exists($config['service'])) {
                return $app->make($config['service']);
            }

            $driver = $config['driver'] ?? 'ipgeolocation';

            $map = [
                'ipapi'   => \Torann\GeoIP\Services\IpApi::class,
                'ipdata'   => \Torann\GeoIP\Services\IpData::class,
                'ipfinder'   => \Torann\GeoIP\Services\IPFinder::class,
                'ipgeolocation'   => \Torann\GeoIP\Services\IPGeoLocation::class,
                'maxminddatabase'   => \Torann\GeoIP\Services\MaxMindDatabase::class,
                'maxmindwebservice'   => \Torann\GeoIP\Services\MaxMindWebService::class,
            ];

            if (!isset($map[$driver])) {
                throw new RuntimeException("Unknown geoip driver [{$driver}]. " .
                    "Please set 'geoip.service' to a FQCN implementing " . ServiceInterface::class .
                    " or add the driver mapping in the provider.");
            }

            $serviceClass = $map[$driver];

            if (!class_exists($serviceClass)) {
                throw new RuntimeException("Configured geoip service class [{$serviceClass}] does not exist.");
            }

            return $app->make($serviceClass);
        });
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
