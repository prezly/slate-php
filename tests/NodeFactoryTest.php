<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Node;
use Prezly\Slate\NodeFactory;

class NodeFactoryTest extends TestCase
{
    /** @var NodeFactory */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new NodeFactory();
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
     * treat them the same
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
            $nodes[] = $this->factory->create($object);
        }

        $this->assertEquals(Node::KIND_BLOCK, $nodes[0]->getKind());
        $this->assertEquals(Node::KIND_TEXT, $nodes[1]->getKind());
        $this->assertEquals(Node::KIND_LEAF, $nodes[2]->getKind());

        $this->assertEquals(4, count($nodes[0]->getChidren()));
        $this->assertEquals(3, count($nodes[1]->getChidren()));
        $this->assertEquals(2, count($nodes[2]->getChidren()));
    }
}
