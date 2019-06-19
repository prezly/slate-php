<?php

namespace Prezly\Slate\Tests\Model;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Tests\TestCase;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_get_concatenated_plain_text_content_of_inner_nodes()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/../fixtures/document_with_text.json');

        $this->assertEquals(
            "I'd like to introduce you to a very important person!",
            $document->getText()
        );
    }

    /**
     * @test
     */
    public function it_should_immutably_set_nodes()
    {
        $data = ['data' => 'x'];

        $nodes_a = [new Block('a')];
        $nodes_b = [new Block('b')];

        $document_a = new Document($nodes_a, $data);
        $document_b = $document_a->withNodes($nodes_b);

        $this->assertNotSame($document_a, $document_b);
        $this->assertSame($nodes_a, $document_a->getNodes());
        $this->assertSame($nodes_b, $document_b->getNodes());
        $this->assertSame($data, $document_a->getData());
        $this->assertSame($data, $document_b->getData());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_data()
    {
        $data_a = ['data' => 'a'];
        $data_b = ['data' => 'b'];

        $nodes = [new Block('x')];

        $document_a = new Document($nodes, $data_a);
        $document_b = $document_a->withData($data_b);

        $this->assertNotSame($document_a, $document_b);
        $this->assertSame($nodes, $document_a->getNodes());
        $this->assertSame($nodes, $document_b->getNodes());
        $this->assertSame($data_a, $document_a->getData());
        $this->assertSame($data_b, $document_b->getData());
    }
}
