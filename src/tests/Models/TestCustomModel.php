<?php

namespace PrionDevelopment\Helper\tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;

class TestCustomModel extends Model
{
    use HasFactory;
    use LookupTrait;

    protected $guarded = ['id', 'created_at'];

    protected $table = 'test_custom_models';

    public ?string $lookupIdColumn = 'custom_id';

    public ?string $lookupStringColumn = 'custom_name';

    public ?string $lookupSlugColumn = 'custom_slug';

    public ?string $lookupUuidColumn = 'custom_uuid';
}
