<?php

namespace PrionDevelopment\Helper\tests\Unit\Models\Traits;

use PrionDevelopment\Helper\Exceptions\FixDefaultColumnOnModelException;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;
use PrionDevelopment\Helper\tests\HelperBaseTest;
use PrionDevelopment\Helper\tests\Models\TestCustomMismatchModel;
use PrionDevelopment\Helper\tests\Models\TestCustomModel;
use PrionDevelopment\Helper\tests\Models\TestModel;

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

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);

        /** @var TestModel $lookup */
        $lookup = $testModel
            ->setCreate(true)
            ->lookup($testName);

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
        $lookup = $testModel
            ->setCreate(true)
            ->lookup($testName);

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
        $this->expectException(FixDefaultColumnOnModelException::class);

        /** @var TestModel $lookup */
        $lookup = $testModel
            ->setCreate(true)
            ->lookup($testName);
    }

    /**
     * @dataProvider badValues
     */
    public function test_lookup_by_uuid_bad_values($testName)
    {
        // Setup
        $lookup = app(TestModel::class)
            ->setCreate(true)
            ->lookup($testName);

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