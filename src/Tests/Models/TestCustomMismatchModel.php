<?php

namespace PrionDevelopment\Helper\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;

class TestCustomMismatchModel extends Model
{
    use HasFactory;
    use LookupTrait;

    protected $guarded = ['id', 'created_at'];

    protected $table = 'test_custom_mismatch';

    public ?string $lookupStringColumn = 'custom_name';

    public ?string $lookupSlugColumn = 'custom_slug';

    public ?string $lookupUuidColumn = 'custom_uuid';
}
