<?php

namespace PrionDevelopment\Helper\tests\Unit\Models\Traits;

use Illuminate\Support\Str;
use PrionDevelopment\Helper\Exceptions\FixDefaultColumnOnModelException;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;
use PrionDevelopment\Helper\tests\HelperBaseTest;
use PrionDevelopment\Helper\tests\Models\TestCustomMismatchModel;
use PrionDevelopment\Helper\tests\Models\TestCustomModel;
use PrionDevelopment\Helper\tests\Models\TestModel;

class LookupTraitBySlugTest extends HelperBaseTest
{
    use LookupTrait;

    public function test_lookup_by_slug_exists()
    {
        $testName = "Test Name String Exists";

        $originalTestModel = TestModel::create([
            'name' => $testName,
            'slug' => Str::slug($testName),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupBySlug($testName);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertSame($originalTestModel->id, $testModelFromLookup->id);
    }

    public function test_lookup_by_slug_not_exists()
    {
        $testName = "Test Name String Not Exists";

        $originalTestModel = TestModel::create([
            'name' => "Will not find string",
            'slug' => Str::slug("Will not find"),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupBySlug($testName);

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
        ]);

        /** @var TestCustomModel $testModel */
        $testModel = app(TestCustomModel::class);
        $testModelFromLookup = $testModel->lookupBySlug($testName);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertSame($originalTestModel->id, $testModelFromLookup->id);
    }

    public function test_lookup_different_column_name()
    {
        $testName = "Test Name String Not Exists";

        $this->expectException(FixDefaultColumnOnModelException::class);

        /** @var TestCustomMismatchModel $testModel */
        $testModel = app(TestCustomMismatchModel::class);
        $testModelFromLookup = $testModel->lookupBySlug($testName);

        // Test Lookup by String
        $this->assertNull($testModelFromLookup);
    }

    /**
     * @dataProvider badValues
     */
    public function test_lookup_by_slug_bad_values($testName)
    {
        $originalTestModel = TestModel::create([
            'name' => "Will not find string",
            'slug' => Str::slug("Will not find"),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupByString($testName);

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
            array(100),
        );
    }
}