<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Node;
use Prezly\Slate\NodeFactory;
use Prezly\Slate\NodeFactory\BaseNodeFactory;
use Prezly\Slate\NodeFactory\NodeFactoryStack;
use Prezly\Slate\Unserializer;

use InvalidArgumentException;
use stdClass;

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
     * The top level node of the Slate model is the Document. Any JSON that doesn't
     * have a top-level document node is invalid
     *
     * @see https://docs.slatejs.org/slate-core/document
     * @test
     */
    public function it_should_expect_top_level_document()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->unserializer->fromJSON("{}");
    }

    /**
     * Since the Slate model always has a top-level node (the Document), the result of unserialization
     * should always be a single Node instance
     *
     * @test
     */
    public function it_should_return_document_node()
    {
        $fixture = $this->loadFixture("00_empty_document.json");
        $node = $this->unserializer->fromJSON($fixture);
        $this->assertEquals(Node::KIND_DOCUMENT, $node->getKind());
        $this->assertEmpty($node->getChildren());
    }

    /**
     * @test
     */
    public function it_should_add_children_to_document()
    {
        $fixture = $this->loadFixture("01_document_with_flat_children.json");
        $document = $this->unserializer->fromJSON($fixture);
        $children = $document->getChildren();

        $this->assertEquals(3, count($children));
        $this->assertEquals(Node::KIND_BLOCK, $children[0]->getKind());
        $this->assertEquals(Node::KIND_INLINE, $children[1]->getKind());
        $this->assertEquals(Node::KIND_TEXT, $children[2]->getKind());
    }

    /**
     * @test
     */
    public function it_should_nest_children()
    {
        $fixture = $this->loadFixture("02_document_with_nested_children.json");
        $document = $this->unserializer->fromJSON($fixture);
        $children = $document->getChildren();

        // Second-level children
        $this->assertEquals(2, count($children[0]->getChildren()));
        $this->assertEquals(0, count($children[1]->getChildren()));
        $this->assertEquals(2, count($children[2]->getChildren()));

        // Third-level children
        $block_children = $children[0]->getChildren();
        $this->assertEquals(Node::KIND_INLINE, $block_children[0]->getKind());
        $this->assertEquals(Node::KIND_TEXT, $block_children[1]->getKind());

        $text_children = $children[2]->getChildren();
        $this->assertEquals(Node::KIND_LEAF, $text_children[0]->getKind());
        $this->assertEquals(Node::KIND_LEAF, $text_children[1]->getKind());
    }

    /**
     * The user should be able to use custom node factories with unserializer, to support custom node types
     *
     * @test
     */
    public function it_should_use_custom_factory_stack()
    {
        $fixture = $this->loadFixture("01_document_with_flat_children.json");
        $node = $this->createMock(Node::class);

        $i = 0;
        $node_kinds = ["document", "block", "inline", "text"];

        $factory = $this->createMock(NodeFactory::class);
        $factory->expects($this->exactly(4))
            ->method("create")
            ->withConsecutive(
                $this->callback(function (stdClass $object) use (&$i, $node_kinds) {
                    $this->assertEquals($node_kinds[$i++], $object->kind);
                    return true;
                })
            )
            ->willReturnOnConsecutiveCalls(
                null, null, $node, null
            );

        $factory_stack = new NodeFactoryStack([
            $factory,
            new BaseNodeFactory()
        ]);

        $unserializer = new Unserializer($factory_stack);

        $document = $unserializer->fromJSON($fixture);
        $children = $document->getChildren();
        $this->assertEquals(3, count($children));
        $this->assertSame($node, $children[1]);
    }
}
