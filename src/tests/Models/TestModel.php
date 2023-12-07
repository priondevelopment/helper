<?php

namespace PrionDevelopment\Helper\tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;

class TestModel extends Model
{
    use HasFactory;
    use LookupTrait;

    protected $guarded = ['id', 'created_at'];

    protected $table = 'test_models';
}
