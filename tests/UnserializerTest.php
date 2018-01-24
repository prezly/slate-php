<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Unserializer;

use InvalidArgumentException;

class UnserializerTest extends TestCase
{
    /** @var Unserializer */
    private $unserializer;

    protected function setUp()
    {
        parent::setUp();
        $this->unserializer = new Unserializer();
    }

    private function getDocumentFromFixture(string $fixture_name): Document
    {
        $fixture = $this->loadFixture($fixture_name);
        $value = $this->unserializer->fromJSON($fixture);
        return $value->getDocument();
    }

    /**
     * The top level node of the Slate model is the Value. Any JSON that doesn't
     * have a top-level document node is invalid
     *
     * @see https://docs.slatejs.org/slate-core/value
     * @see https://docs.slatejs.org/slate-core/document
     * @test
     */
    public function it_should_expect_top_level_document()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->unserializer->fromJSON("{}");
    }

    /**
     * Since the Slate model always has a top-level node (the Value), the result of unserialization
     * should always be a single Value instance
     *
     * @test
     */
    public function it_should_return_document_node()
    {
        $fixture_name = "empty_document.json";
        $document = $this->getDocumentFromFixture($fixture_name);
        $this->assertEmpty($document->getNodes());
    }

    public function it_should_only_accept_block_nodes_for_document()
    {
        $fixture = $this->loadFixture("document_with_inline_children.json");
        $this->expectException(InvalidArgumentException::class);
        $this->unserializer->fromJSON($fixture);
    }

    /**
     * @test
     */
    public function it_should_add_children_to_document()
    {
        $document = $this->getDocumentFromFixture("document_with_flat_children.json");
        $nodes = $document->getNodes();

        $this->assertEquals(3, count($nodes));
        foreach ($nodes as $node) {
            $this->assertInstanceOf(Block::class, $node);
        }

        $this->assertEquals("paragraph", $nodes[0]->getType());
        $this->assertEquals("blockquote", $nodes[1]->getType());
        $this->assertEquals("list", $nodes[2]->getType());
    }

    /**
     * @test
     */
    public function it_should_nest_children()
    {
        $document = $this->getDocumentFromFixture("document_with_nested_children.json");
        $children = $document->getNodes();

        // Second-level children
        $this->assertEquals(2, count($children[0]->getNodes()));
        $this->assertEquals(0, count($children[1]->getNodes()));
        $this->assertEquals(2, count($children[2]->getNodes()));
    }

    /**
     * @test
     */
    public function it_should_load_document_with_leaves()
    {
        $document = $this->getDocumentFromFixture("document_with_text.json");

        $this->assertCount(1, $document->getNodes());
        $block = $document->getNodes()[0];

        /** @var Block $block */
        $this->assertInstanceOf(Block::class, $block);
        $this->assertEquals('paragraph', $block->getType());
        $this->assertCount(1, $block->getNodes());

        /** @var Text $text */
        $text = $block->getNodes()[0];
        $this->assertInstanceOf(Text::class, $text);
        $this->assertCount(6, $text->getLeaves());

        $expected_texts = [
            "I'd like to introduce ",
            'you',
            ' to a ',
            'very important ',
            'person',
            '!',
        ];

        foreach ($text->getLeaves() as $i => $leaf) {
            $this->assertInstanceOf(Leaf::class, $leaf);
            $this->assertEquals($expected_texts[$i], $leaf->getText());
        }
    }

    /**
     * @test
     */
    public function it_should_set_node_data()
    {
        $document = $this->getDocumentFromFixture("nodes_with_data.json");
        $block = $document->getNodes()[0];
        $this->assertEquals(["foo" => "bar"], $block->getData());

        /** @var Inline $inline */
        $inline = $block->getNodes()[0];
        $this->assertEquals(["name" => "John Doe", "id" => 1234], $inline->getData());
    }
}
