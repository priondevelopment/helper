<?php

namespace PrionDevelopment\Helper\Models\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PrionDevelopment\Helper\Exceptions\FixDefaultColumnOnModelException;
use Ramsey\Uuid\Uuid;
use function PHPUnit\Framework\isEmpty;

trait LookupTrait
{
    public ?string $defaultLookupIdColumn = 'id';

    public ?string $defaultLookupStringColumn = 'name';

    public ?string $defaultLookupSlugColumn = 'slug';

    public ?string $defaultLookupUuidColumn = 'uuid';

    protected bool $shouldCreate = false;

    protected bool $shouldCache = true;

    /**
     * How long we will cache the model value
     *
     * @var int
     */
    protected int $cacheTtl = 15;

    protected string $prefix = 'lookup';

    /**
     * Lookup a Database Record from String, Slug or ID
     *
     * @param string|int|null $value
     * @param array $data
     *
     * @return LookupTrait|\PrionDevelopment\Helper\tests\Unit\Models\Traits\LookupTraitTest|null
     */
    public function lookup(
        string|int|null $value
        , array $data = []
    ): ?self
    {
        // Standardize the String
        $value = standardizeString($value);

        // Will always return null if empty string
        if (empty($value)) {
            return null;
        }

        // Integer
        $response = $this->lookupById($value);

        if (null !== $response) {
            return $response;
        }


        // Uuid
        $response = $this->lookupByUuid($value);

        if (null !== $response) {
            return $response;
        }


        // String
        $response = $this->lookupByString($value);

        if (null !== $response) {
            return $response;
        }

        // Slug
        $response = $this->lookupBySlug($value);

        if (null !== $response) {
            return $response;
        }

        return $this->lookupCreate($value, $data);
    }

    public function lookupCreate(
        string|int|null $value
        , array $data = []
    )
    {
        if (true !== $this->shouldCreate) {
            return null;
        }

        // Will only create a string
        if (
            empty($value) ||
            is_int($value) ||
            Uuid::isValid($value)
        ) {
            return null;
        }

        $column = $this->getStringColumn();
        $data[$column] = $value;

        return self::create($data);
    }

    /**
     * Lookup by String
     *
     * @param string|int|null $value
     *
     * @return mixed|null
     */
    public function lookupByString(string|int|null $value)
    {
        // Standardize the String
        $value = standardizeString($value);
        $column = $this->getStringColumn();

        if (empty($value)) {
            return null;
        }

        if (
            ! is_string($value)
            || false === $this->columnExists($column)
        ) {
            return null;
        }

        return Cache::remember($this->lookupCacheKey($value), $this->lookupTtl(), function () use ($column, $value) {
            return $this->where($column, $value)->first();
        });
    }

    /**
     * Lookup by Slug
     *
     * @param string|int|null $value
     *
     * @return mixed|null
     */
    public function lookupBySlug(string|int|null $value)
    {
        // Standardize the String
        $value = standardizeString($value);
        $value = Str::slug($value);
        $column = $this->getSlugColumn();

        if (
            ! is_string($value)
            || false === $this->columnExists($column)
        ) {
            return null;
        }

        return Cache::remember($this->lookupCacheKey($value), $this->lookupTtl(), function () use ($column, $value) {
            return $this->where($column, $value)->first();
        });
    }

    /**
     * Find Lookups using Integer
     *
     * @param string|int|null $value
     *
     * @return mixed|null
     */
    public function lookupById(string|int|null $value)
    {
        // Standardize the String
        $column = $this->getIdColumn();

        if (
            ! is_int($value)
            || false === $this->columnExists($column)
        ) {
            return null;
        }

        return Cache::remember($this->lookupCacheKey($value), $this->lookupTtl(), function () use ($column, $value) {
            return $this->where($column, $value)->first();
        });
    }

    /**
     * Lookup Database by Uuid
     *
     * @param string|int|null $value
     *
     * @return mixed|null
     */
    public function lookupByUuid(string|int|null $value)
    {
        // Standardize the String
        $value = standardizeString($value);
        $column = $this->getUuidColumn();

        if (false === Uuid::isValid($value)
            || false === $this->columnExists($column)
        ) {
            return null;
        }

        $uuid = Uuid::fromString($value);
        $uuidBinary = $uuid->getBytes();

        return Cache::remember($this->lookupCacheKey($value), $this->lookupTtl(), function () use ($column, $value) {
            return $this->where($column, $value)->first();
        });
    }

    /**
     * Check if the column exists on the current table.
     *
     * @param string|null $columnName
     *
     * @return bool
     */
    public function columnExists(?string $columnName): bool
    {
        $key = $this->columnExistsCacheKey($columnName);
        return Cache::remember($key, 30, function () use ($columnName) {
            $hasColumn = Schema::hasColumn($this->getTable(),$columnName);

            if (false === $hasColumn) {
                $class = get_class($this);
                throw new FixDefaultColumnOnModelException("Column '{$columnName}' does not exist on model {$class}");
            }

            return $hasColumn;
        });
    }

    /**
     * Create a Cache Key for Column Lookup
     *
     * @param string $columnName
     *
     * @return string
     */
    public function columnExistsCacheKey(string $columnName)
    {
        $parentClass = get_class($this);
        $parentClass = $this->cacheKeyStandardize($parentClass);

        return "{$parentClass}:{$columnName}";
    }

    /**
     * Create a consistent cache key based on the parent model
     *
     * @param string|int|null $value
     *
     * @return string
     */
    public function lookupCacheKey(string|int|null $value): string
    {
        $parentClass = get_class($this);
        $parentClass = $this->cacheKeyStandardize($parentClass);

        return "{$this->prefix}:{$parentClass}:{$value}";
    }

    /**
     * Standardize the Cache Key
     *
     * @param string $key
     *
     * @return string
     */
    public function cacheKeyStandardize(string $key): string
    {
        $key = str_replace('\\', '_', $key);
        $key = strtolower($key);

        return $key;
    }

    /**
     * Use the flush cache key to clear the cache.
     *
     * @param string|int|null $value
     * @param array $data
     * @param bool $cache
     *
     * @return self
     */
    public function lookupFlushCache(
        string|int|null $value
        , array $data = []
        , bool $cache = true
    ): self
    {
        Cache::forget($this->lookupCacheKey($value));
        return $this;
    }

    public function lookupTtl(): ?int
    {
        return $this->shouldCache ? $this->cacheTtl : 0;
    }

    public function setCache(bool|null $shouldCache): self
    {
        $this->shouldCache = boolval($shouldCache);
        return $this;
    }

    public function setCreate(bool|null $setCreate): self
    {
        $this->shouldCreate = boolval($setCreate);
        return $this;
    }

    public function getIdColumn(): string
    {
        return $this->getLookupColumn('id');
    }

    public function getSlugColumn(): string
    {
        return $this->getLookupColumn('slug');
    }

    public function getStringColumn(): string
    {
        return $this->getLookupColumn('string');
    }

    public function getUuidColumn(): string
    {
        return $this->getLookupColumn('uuid');
    }

    /**
     * Pull either the default column from the model or default from the trait
     *
     * @param string $column
     *
     * @return string
     */
    public function getLookupColumn(string $column): string
    {
        $column = ucfirst($column);
        $modelVariable = "lookup{$column}Column";
        $defaultVariable = "default" . ucfirst($modelVariable);

        return !empty($this->$modelVariable) ? $this->$modelVariable : $this->$defaultVariable;
    }
}