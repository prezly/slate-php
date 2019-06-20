<?php
namespace Prezly\Slate\Tests\Serialization;

use InvalidArgumentException;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Exceptions\UnsupprotedVersionException;
use Prezly\Slate\Serialization\Serializer;
use Prezly\Slate\Tests\TestCase;

class SerializerTest extends TestCase
{
    private function serializer(string $version = null): Serializer
    {
        return new Serializer($version);
    }

    /**
     * @test
     */
    public function it_should_serialize_value_to_json()
    {
        $value = new Value(new Document());

        $json = $this->serializer()->toJson($value);
        $this->assertJson($json);

        return $json;
    }

    /**
     * @test
     * @depends it_should_serialize_value_to_json
     * @param string $json
     */
    public function it_should_unserialize_value_from_json(string $json)
    {
        $value = new Value(new Document());

        $this->assertEquals($value, $this->serializer()->fromJson($json));
    }


    /**
     * @test
     */
    public function it_should_store_version_upon_serialization()
    {
        $json = $this->serializer()->toJson(new Value(new Document()));

        $this->assertArrayHasKey('version', json_decode($json, true));
    }

    /**
     * @test
     */
    public function it_should_serialize_to_different_versions()
    {
        $value = new Value(new Document());

        $json_v0_44 = $this->serializer()->toJson($value, '0.44');
        $json_v0_44_13 = $this->serializer()->toJson($value, '0.44.13');
        $json_v0_45 = $this->serializer()->toJson($value, '0.45');
        $json_v0_45_1 = $this->serializer()->toJson($value, '0.45.1');

        $this->assertArraySubset(['version' => '0.44'], json_decode($json_v0_44, true));
        $this->assertArraySubset(['version' => '0.44.13'], json_decode($json_v0_44_13, true));
        $this->assertArraySubset(['version' => '0.45'], json_decode($json_v0_45, true));
        $this->assertArraySubset(['version' => '0.45.1'], json_decode($json_v0_45_1, true));

        $this->assertEquals($value, $this->serializer()->fromJson($json_v0_44));
        $this->assertEquals($value, $this->serializer()->fromJson($json_v0_44_13));
        $this->assertEquals($value, $this->serializer()->fromJson($json_v0_45));
        $this->assertEquals($value, $this->serializer()->fromJson($json_v0_45_1));
    }

    /**
     * @test
     */
    public function it_should_serialize_to_latest_version_by_default()
    {
        $value = new Value(new Document());
        $json = $this->serializer()->toJson($value);

        $this->assertArraySubset(['version' => Serializer::LATEST_SERIALIZATION_VERSION], json_decode($json, true));
        $this->assertEquals($value, $this->serializer()->fromJson($json));
    }

    /**
     * @test
     */
    public function it_should_be_possible_to_set_default_serialization_version()
    {
        $value = new Value(new Document());
        $json = $this->serializer('0.44')->toJson($value);

        $this->assertNotEquals(Serializer::LATEST_SERIALIZATION_VERSION, '0.44');
        $this->assertArraySubset(['version' => '0.44'], json_decode($json, true));
        $this->assertEquals($value, $this->serializer()->fromJson($json));
    }

    /**
     * @test
     */
    public function it_should_throw_on_unsupported_serialization_version()
    {
        $this->expectException(UnsupprotedVersionException::class);
        $this->expectExceptionMessage('Unsupported serialization version requested: 0.20.1');
        $this->serializer()->toJson(new Value(new Document()), '0.20.1');
    }

    /**
     * @test
     * @dataProvider invalid_documents_fixtures
     *
     * @param string $file
     * @param string $expected_error
     */
    public function it_should_fail_unserializing_invalid_structure(string $file, string $expected_error)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expected_error);
        $this->serializer()->fromJson($this->loadFixture($file));
    }

    public function valid_documents_jsons(): array
    {
        return [
            'document_with_text.json' => [__DIR__ . '/fixtures/document_with_text.json'],
        ];
    }

    /**
     * @see it_should_fail_unserializing_invalid_structure
     */
    public function invalid_documents_fixtures()
    {
        return [
            [__DIR__ . '/fixtures/invalid_document_01_not_an_object_int.json', 'Unexpected JSON value given: integer. An object is expected to construct Value.'],
            [__DIR__ . '/fixtures/invalid_document_02_not_an_object_string.json', 'Unexpected JSON value given: string. An object is expected to construct Value.'],
            [__DIR__ . '/fixtures/invalid_document_03_empty_object.json', 'Invalid JSON structure given to construct Value. It should have "object" property.'],
            [__DIR__ . '/fixtures/invalid_document_04_invalid_value_object.json', 'Invalid JSON structure given to construct Value. It should have "object" property set to "value".'],
            [__DIR__ . '/fixtures/invalid_document_05_missing_document.json', 'Unexpected JSON structure given for Value. A Value should have "document" property.'],
            [__DIR__ . '/fixtures/invalid_document_06_null_document.json', 'Unexpected JSON structure given for Value. The "document" property should be object.'],
            [__DIR__ . '/fixtures/invalid_document_07_invalid_document_object.json', 'Invalid JSON structure given to construct Document. It should have "object" property set to "document".'],
            [__DIR__ . '/fixtures/invalid_document_08_invalid_leaf_object.v0.40.json', 'Invalid JSON structure given to construct Leaf. It should have "object" property set to "leaf".'],
            [__DIR__ . '/fixtures/invalid_document_09_invalid_text_object.v0.46.json', 'Unexpected JSON structure given for Text. A Text should have "text" property.'],
        ];
    }
}
