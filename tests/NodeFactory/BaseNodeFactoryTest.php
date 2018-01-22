<?php

namespace Prezly\Slate\Tests\NodeFactory;

use Prezly\Slate\Node;
use Prezly\Slate\Node\LeafNode;
use Prezly\Slate\NodeFactory\BaseNodeFactory;
use Prezly\Slate\NodeFactory\NodeFactoryStack;
use Prezly\Slate\Tests\TestCase;

class BaseNodeFactoryTest extends TestCase
{
    /** @var BaseNodeFactory */
    private $factory;

    /** @var NodeFactoryStack */
    private $factory_stack;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new BaseNodeFactory();
        $this->factory_stack = new NodeFactoryStack([$this->factory]);
    }

    /**
     * At the basic conceptual level, all nodes have the same basic hierarchical structure: they have a kind and
     * optional children. However, depending on the node kind, not all children are referenced the same way:
     *
     * - `block` nodes have the `nodes` property
     * - `text` nodes have the `leaves` property
     * - `leaf` nodes have the `marks` property
     *
     * Despite the properties being named differently, all these, in essence, represent a node's children, so we should
     * treat them the same, and them differentiate them in the renderers.
     *
     * @test
     */
    public function it_should_add_children_to_specific_node_types()
    {
        $fixture = $this->loadFixture("03_node_types.json");
        $data = json_decode($fixture, false);

        /** @var Node[] $nodes */
        $nodes = [];
        foreach ($data as $object) {
            $nodes[] = $this->factory->create($object, $this->factory_stack);
        }

        $this->assertEquals(Node::KIND_BLOCK, $nodes[0]->getKind());
        $this->assertEquals(Node::KIND_TEXT, $nodes[1]->getKind());
        $this->assertEquals(Node::KIND_LEAF, $nodes[2]->getKind());

        $this->assertEquals(4, count($nodes[0]->getChidren()));
        $this->assertEquals(3, count($nodes[1]->getChidren()));
        $this->assertEquals(2, count($nodes[2]->getChidren()));
    }

    /**
     * Nodes with specific behavior should be implemented as subclasses of Node. For example, unlike other node types,
     * the leaf node has a text
     *
     * @test
     */
    public function it_should_create_leaf_instances()
    {
        $fixtures = [
            $this->loadFixture("04_leaf_without_text.json"),
            $this->loadFixture("05_leaf_with_text.json"),
        ];

        /** @var LeafNode[] $nodes */
        $nodes = [];
        foreach ($fixtures as $json) {
            $nodes[] = $this->factory->create(json_decode($json, false), $this->factory_stack);
        }

        $this->assertInstanceOf(LeafNode::class, $nodes[0]);
        $this->assertInstanceOf(LeafNode::class, $nodes[1]);

        $this->assertNull($nodes[0]->getText());
        $this->assertEquals("Foo bar baz", $nodes[1]->getText());
    }
}
