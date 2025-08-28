<?php

namespace Torann\GeoIP\Services;

use Exception;
use Illuminate\Support\Arr;
use Torann\GeoIP\Location;
use Torann\GeoIP\Support\HttpClient;

/**
 * Class GeoIP
 * @package Torann\GeoIP\Services
 */
class IPFinder extends AbstractService
{
    /**
     * Http client instance.
     *
     * @var HttpClient
     */
    protected HttpClient $client;

    /**
     * The "booting" method of the service.
     *
     * @return void
     */
    #[\Override]
    public function boot(): void
    {
        $this->client = new HttpClient([
            'base_uri' => 'https://api.ipfinder.io/v1/',
            'headers' => [
                'User-Agent' => 'Laravel-GeoIP-Torann',
            ],
            'query'    => [
                'token' => $this->config('key'),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function locate($ip): Location
    {
        // Get data from client
        $data = $this->client->get($ip);

        // Verify server response
        if ($this->client !== null || empty($data[0])) {
            throw new Exception('Request failed (' . $this->client . ')');
        }

        $json = json_decode((string) $data[0], true);

        return $this->hydrate($json);
    }
}
