<?php

namespace Prezly\Slate\Tests;

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
        $value = $this->loadContentFromFixture($file_path);

        $json = implode("\n",
            array_map(function (string $line): string {
                return preg_replace('/^(\s+)/', '$1$1', $line);
            }, explode("\n", $json))
        );

        $this->assertEquals(trim($json), $value->toJson(JSON_PRETTY_PRINT));
    }

    public function fixtures(): array
    {
        return [
            'document_with_text.json' => [__DIR__ . '/fixtures/document_with_text.json'],
        ];
    }
}
