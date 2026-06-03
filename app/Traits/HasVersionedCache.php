<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasVersionedCache
{
    /**
     * Get the cache key prefix for versioning
     */
    abstract protected function getCacheKeyPrefix(): string;

    /**
     * Get the current cache version to enable cache busting
     */
    protected function getCacheVersion(): int
    {
        return (int) Cache::rememberForever($this->getCacheKeyPrefix() . ':cache_version', fn () => 1);
    }

    /**
     * Increment cache version to bust all cache keys
     */
    protected function clearCache(): void
    {
        $key = $this->getCacheKeyPrefix() . ':cache_version';
        $version = (int) Cache::get($key, 1);
        Cache::put($key, $version + 1, now()->addDays(30));
    }
}
