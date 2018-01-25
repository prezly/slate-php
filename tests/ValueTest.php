<?php

namespace Prezly\Slate\Tests;

class ValueTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_serialize_to_json()
    {
        $json = $this->loadFixture(__DIR__ . '/fixtures/document_with_text.json');
        $value = $this->loadContentFromFixture(__DIR__ . '/fixtures/document_with_text.json');

        $json = implode("\n",
            array_map(function(string $line): string {
                return preg_replace('/^(\s+)/', '$1$1', $line);
            }, explode("\n", $json))
        );

        $this->assertEquals(trim($json), $value->toJson(JSON_PRETTY_PRINT));
    }
}
