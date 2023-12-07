<?php

namespace PrionDevelopment\Helper\tests\Unit\Models\Traits;

use Illuminate\Support\Str;
use PrionDevelopment\Helper\Exceptions\FixDefaultColumnOnModelException;
use PrionDevelopment\Helper\Models\Traits\LookupTrait;
use PrionDevelopment\Helper\tests\HelperBaseTest;
use PrionDevelopment\Helper\tests\Models\TestCustomMismatchModel;
use PrionDevelopment\Helper\tests\Models\TestCustomModel;
use PrionDevelopment\Helper\tests\Models\TestModel;
use Ramsey\Uuid\Uuid;

class LookupTraitByUuidTest extends HelperBaseTest
{
    use LookupTrait;

    public function test_lookup_by_uuid_exists()
    {
        $testName = "Uuid test";
        $testUuid = Uuid::uuid7();

        $originalTestModel = TestModel::create([
            'name' => $testName,
            'slug' => Str::slug($testName),
            'uuid' => $testUuid->toString(),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupByUuid($testUuid);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertSame($originalTestModel->id, $testModelFromLookup->id);
    }

    public function test_lookup_by_uuid_not_exists()
    {
        $testName = "Test Name String Not Exists";
        $testUuid1 = Uuid::uuid7();
        $testUuid2 = Uuid::uuid7();

        $originalTestModel = TestModel::create([
            'name' => "Will not find string",
            'slug' => Str::slug("Will not find"),
            'uuid' => $testUuid1->toString(),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupByUuid($testUuid2);

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertNull($testModelFromLookup);
    }

    public function test_lookup_different_uuid_column()
    {
        $testName = "Test Name String Exists";
        $testUuid1 = Uuid::uuid7();

        $originalTestModel = TestCustomModel::create([
            'custom_name' => $testName,
            'custom_slug' => Str::slug($testName),
            'custom_uuid' => $testUuid1->toString(),
        ]);

        /** @var TestCustomModel $testModel */
        $testModel = app(TestCustomModel::class);
        $testModelFromLookup = $testModel->lookupByUuid($testUuid1->toString());

        // Test Lookup by String
        $this->assertNotNull($originalTestModel->id);
        $this->assertSame($originalTestModel->id, $testModelFromLookup->id);
    }

    public function test_lookup_different_column_name()
    {
        $testName = "Test Name String Not Exists";
        $testUuid1 = Uuid::uuid7();

        $this->expectException(FixDefaultColumnOnModelException::class);

        /** @var TestCustomMismatchModel $testModel */
        $testModel = app(TestCustomMismatchModel::class);
        $testModelFromLookup = $testModel->lookupByUuid($testUuid1);

        // Test Lookup by String
        $this->assertNull($testModelFromLookup);
    }

    /**
     * @dataProvider badValues
     */
    public function test_lookup_by_uuid_bad_values($testName)
    {
        $originalTestModel = TestModel::create([
            'name' => "Will not find string",
            'slug' => Str::slug("Will not find"),
        ]);

        /** @var TestModel $testModel */
        $testModel = app(TestModel::class);
        $testModelFromLookup = $testModel->lookupByUuid($testName);

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