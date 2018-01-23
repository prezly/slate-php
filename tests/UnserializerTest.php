<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Node\Block;
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
        $fixture = $this->loadFixture("empty_document.json");
        $document = $this->unserializer->fromJSON($fixture)->getDocument();
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
        $fixture = $this->loadFixture("document_with_flat_children.json");
        $document = $this->unserializer->fromJSON($fixture)->getDocument();
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
        $fixture = $this->loadFixture("document_with_nested_children.json");
        $value = $this->unserializer->fromJSON($fixture);
        $document = $value->getDocument();
        $children = $document->getNodes();

        // Second-level children
        $this->assertEquals(2, count($children[0]->getNodes()));
        $this->assertEquals(0, count($children[1]->getNodes()));
        $this->assertEquals(2, count($children[2]->getNodes()));
    }
}
