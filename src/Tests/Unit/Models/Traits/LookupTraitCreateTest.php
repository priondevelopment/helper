<?php

namespace PrionDevelopment\Helper\Tests\Unit\Models\Traits;

use PrionDevelopment\Helper\Models\Traits\LookupTrait;
use PrionDevelopment\Helper\Tests\HelperBaseTest;
use PrionDevelopment\Helper\Tests\Models\TestCustomMismatchModel;
use PrionDevelopment\Helper\Tests\Models\TestCustomModel;
use PrionDevelopment\Helper\Tests\Models\TestModel;

class LookupTraitCreateTest extends HelperBaseTest
{
    use LookupTrait;

    public function test_will_not_create()
    {
        // Setup
        $testName = "Uuid test will not create";
        $lookup = app(TestModel::class)->lookup($testName);

        // Assert
        $this->assertNull($lookup);
    }

    public function test_will_create()
    {
        // Setup
        $testName = "Uuid test will create";

        /** @var TestModel $lookup */
        $lookup = TestModel::lookup(
                value: $testName,
                create: true
            );

        // Assert
        $this->assertSame($testName, $lookup->name);
    }

    public function test_will_create_custom_column()
    {
        // Setup
        $testName = "Uuid test will create custom column";

        /** @var TestModel $testModel */
        $testModel = app(TestCustomModel::class);

        /** @var TestModel $lookup */
        $lookup = TestCustomModel::lookup(
                value: $testName,
                create: true
            );

        // Assert
        $this->assertSame($testName, $lookup->custom_name);
    }

    public function test_will_throw_create_custom_mismatch_column()
    {
        // Setup
        $testName = "Uuid test will create custom column";

        /** @var TestModel $testModel */
        $testModel = app(TestCustomMismatchModel::class);

        // Assert
        /** @var TestModel $lookup */
        $lookup = $testModel::lookup(
            value: $testName,
            create: true
        );
    }

    /**
     * @dataProvider badValues
     */
    public function test_lookup_by_uuid_bad_values($testName)
    {
        // Setup
        $lookup = TestModel::lookup(
            value: $testName,
            create: true
        );

        // Assert
        $this->assertNull($lookup);
    }

    public function badValues()
    {
        return array(
            array(""),
            array(" "),
            array(null),
            array("  "),
            array(100),
        );
    }
}