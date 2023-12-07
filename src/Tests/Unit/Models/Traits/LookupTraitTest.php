<?php

namespace PrionDevelopment\Helper\Tests\Unit\Models\Traits;

use Illuminate\Support\Facades\Cache;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;
use PrionDevelopment\Helper\Tests\HelperBaseTest;

class LookupTraitTest extends HelperBaseTest
{
    use LookupTrait;

    public function test_set_create_false()
    {
        // Reset in can different
        $this->setCreate(false);

        // False by Default
        $this->assertFalse($this->shouldCreate);

        $this->setCreate(true);
        $this->assertTrue($this->shouldCreate);

        $this->setCreate(false);
        $this->assertFalse($this->shouldCreate);
    }

    public function test_set_create_null()
    {
        $this->setCreate(null);
        $this->assertFalse($this->shouldCreate);
    }

    public function test_set_cache()
    {
        // True by default
        $this->assertTrue($this->shouldCache);

        $this->setCache(false);
        $this->assertFalse($this->shouldCache);

        $this->setCache(true);
        $this->assertTrue($this->shouldCache);

        $this->setCache(null);
        $this->assertFalse($this->shouldCache);
    }

    public function test_cache_ttl_after_cache()
    {
        $this->setCache(false);
        $this->assertSame(0, $this->lookupTtl());

        $this->setCache(true);
        $this->assertSame($this->cacheTtl, $this->lookupTtl());
    }

    public function test_cache_key()
    {
        // Setup
        $key = $this->lookupCacheKey("test");

        // Assert
        $this->assertStringStartsWith("lookup:", $key);
        $this->assertStringEndsWith(":test", $key);
        $this->assertStringContainsString("lookuptraittest", $key);
    }

    public function test_flush_cache()
    {
        // Setup
        $testValue = "test_value";
        $key = $this->lookupCacheKey("test");
        Cache::forget($key);

        $this->assertNull(Cache::get($key));

        Cache::put($key, $testValue);

        // Run
        $this->assertSame($testValue, Cache::get($key));
        $this->lookupFlushCache("test");
        $this->assertNull(Cache::get($key));
    }

    public function test_does_not_flush_different_cache()
    {
        // Setup
        $key = $this->lookupCacheKey("test");
        $key2 = $this->lookupCacheKey("test2");
        $testValue = "test_value";

        $this->assertNull(Cache::get($key));
        $this->assertNull(Cache::get($key2));

        Cache::put($key, $testValue);

        // Run
        $this->assertSame($testValue, Cache::get($key));
        $this->assertNull(Cache::get($key2));

        $this->lookupFlushCache("test2");
        $this->assertSame($testValue, Cache::get($key));
    }

    public function test_lookup_by_string_empty()
    {
        $this->assertNull($this->lookupByString(null));
        $this->assertNull($this->lookupByString(''));
        $this->assertNull($this->lookupByString(' '));
    }
}