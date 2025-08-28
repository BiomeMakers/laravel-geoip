<?php

namespace Torann\GeoIP;

use Illuminate\Cache\{CacheManager, TaggedCache};

class Cache
{
    /**
     * Instance of cache manager.
     *
     * @var CacheManager|TaggedCache
     */
    protected CacheManager|TaggedCache $cache;

    /**
     * Create a new cache instance.
     *
     * @param CacheManager $cache
     * @param array|null $tags
     * @param int $expires  Lifetime of the cache.
     */
    public function __construct(CacheManager $cache, ?array $tags, protected int $expires = 30)
    {
        $this->cache = $tags ? $cache->tags($tags) : $cache;
    }

    /**
     * Get an item from the cache.
     *
     * @param string $name
     *
     * @return Location|null
     */
    public function get(string $name): ?Location
    {
        $value = $this->cache->get($name);

        return is_array($value)
            ? new Location($value)
            : null;
    }

    /**
     * Store an item in cache.
     *
     * @param string $name
     * @param Location $location
     *
     * @return bool
     */
    public function set(string $name, Location $location): bool
    {
        return $this->cache->put($name, $location->toArray(), $this->expires);
    }

    /**
     * Flush cache for tags.
     *
     * @return bool
     */
    public function flush(): bool
    {
        return $this->cache->flush();
    }
}
