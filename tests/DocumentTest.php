<?php
namespace Prezly\Slate\Tests;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_get_concatenated_plain_text_content_of_inner_nodes()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_text.json');

        $this->assertEquals(
            "I'd like to introduce you to a very important person!",
            $document->getText()
        );
    }
}
