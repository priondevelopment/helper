<?php

namespace PrionDevelopment\Helper\tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class HelperBaseTest extends HelperTestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function testDatabaseTables()
    {
        $this->assertCount(0, \Illuminate\Support\Facades\DB::table('test_models')->get());
        $this->assertCount(0, \Illuminate\Support\Facades\DB::table('test_custom_models')->get());
    }
}
