<?php

namespace Torann\GeoIP\Services;

use Torann\GeoIP\Location;
use Illuminate\Support\Arr;
use Torann\GeoIP\Contracts\ServiceInterface;

abstract class AbstractService implements ServiceInterface
{
    /**
     * Driver config
     *
     * @var array
     */
    protected array $config;

    /**
     * Create a new service instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;

        $this->boot();
    }

    /**
     * The "booting" method of the service.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate(array $attributes = []): Location
    {
        return new Location($attributes);
    }

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function config(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }
}
