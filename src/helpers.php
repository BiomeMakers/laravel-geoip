<?php

use Torann\GeoIP\{GeoIP, Location};

if (!function_exists('geoip')) {
    /**
     * Get the location of the provided IP.
     *
     * @param string|null $ip
     *
     * @return GeoIP|Location
     */
    function geoip(?string $ip = null)
    {
        if (is_null($ip)) {
            return app('geoip');
        }

        return app('geoip')->getLocation($ip);
    }
}
