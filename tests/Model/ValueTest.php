<?php

namespace Prezly\Slate\Tests\Model;

use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Tests\TestCase;

class ValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider fixtures
     *
     * @param string $file_path
     */
    public function it_should_serialize_to_json(string $file_path)
    {
        $json = $this->loadFixture($file_path);
        $value = $this->loadValueFromFixture($file_path);

        $json = implode("\n",
            array_map(function (string $line): string {
                return preg_replace('/^(\s+)/', '$1$1', $line);
            }, explode("\n", $json))
        );

        $this->assertEquals(trim($json), $value->toJson(JSON_PRETTY_PRINT));
    }

    /**
     * @test
     */
    public function it_should_immutably_set_document()
    {
        $document_a = new Document();
        $document_b = new Document();

        $value_a = new Value($document_a);
        $value_b = $value_a->withDocument($document_b);

        $this->assertNotSame($value_a, $value_b);
        $this->assertSame($document_a, $value_a->getDocument());
        $this->assertSame($document_b, $value_b->getDocument());
    }

    public function fixtures(): array
    {
        return [
            'document_with_text.json' => [__DIR__ . '/../fixtures/document_with_text.json'],
        ];
    }
}
