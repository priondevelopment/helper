<?php

namespace PrionDevelopment\Helper\tests\Unit\Models\Traits;

use Illuminate\Support\Str;
use PrionDevelopment\Helper\Exceptions\FixDefaultColumnOnModelException;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;
use PrionDevelopment\Helper\tests\HelperBaseTest;
use PrionDevelopment\Helper\tests\Models\TestCustomMismatchModel;
use PrionDevelopment\Helper\tests\Models\TestCustomModel;
use PrionDevelopment\Helper\tests\Models\TestModel;

class LookupTraitByIdTest extends HelperBaseTest
{
    use LookupTrait;

    public function test_lookup_by_id_exists()
    {
        $testName = "Test Name String Exists";

        $originalTestModel = TestModel::create([
            'name' => $testName,
            'slug' => Str::slug($testName),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupById($originalTestModel->id);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertSame($originalTestModel->id, $testModelFromLookup->id);
    }

    public function test_lookup_by_id_not_exists()
    {
        $testName = "Test Name String Not Exists";

        $originalTestModel = TestModel::create([
            'name' => "Will not find string",
            'slug' => Str::slug("Will not find"),
        ]);

        $id = $originalTestModel->id;

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupById($id + 1);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertNull($testModelFromLookup);
    }

    public function test_lookup_different_name_column()
    {
        $testName = "Test Name String Exists";

        $originalTestModel = TestCustomModel::create([
            'custom_name' => $testName,
            'custom_slug' => Str::slug($testName),
            'custom_id' => random_int(1,999999),
        ]);

        /** @var TestCustomModel $testModel */
        $testModel = app(TestCustomModel::class);
        $testModelFromLookup = $testModel->lookupById($originalTestModel->custom_id);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertSame($originalTestModel->id, $testModelFromLookup->id);
    }

    public function test_lookup_different_column_id()
    {
        $testName = "Test Name String Not Exists";

        $this->expectException(FixDefaultColumnOnModelException::class);

        /** @var TestCustomMismatchModel $testModel */
        $testModel = app(TestCustomMismatchModel::class);
        $testModelFromLookup = $testModel->lookupById(100);

        // Test Lookup by String
        $this->assertNull($testModelFromLookup);
    }

    /**
     * @dataProvider badValues
     */
    public function test_lookup_by_id_bad_values($testName)
    {
        $originalTestModel = TestModel::create([
            'name' => "Will not find string",
            'slug' => Str::slug("Will not find"),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupById($testName);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertNull($testModelFromLookup);
    }

    public function badValues()
    {
        return array(
            array(""),
            array(" "),
            array(null),
            array("  "),
            array("Hi"),
            array(" I am invalid"),
        );
    }
}