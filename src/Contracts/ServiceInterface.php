<?php

namespace Torann\GeoIP\Contracts;

use Torann\GeoIP\Location;

interface ServiceInterface
{
    /**
     * The "booting" method of the service.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Determine a location based off of
     * the provided IP address.
     *
     * @param string $ip
     *
     * @return Location
     */
    public function locate(string $ip): Location;

    /**
     * Create a location instance from the provided attributes.
     *
     * @param array $attributes
     *
     * @return Location
     */
    public function hydrate(array $attributes = []): Location;

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function config(string $key, mixed $default = null): mixed;
}
