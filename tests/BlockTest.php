<?php
namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Inline;

class BlockTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_immutably_set_type()
    {
        $type_a = 'block_a';
        $type_b = 'block_b';
        $nodes = [new Inline('x')];
        $data = ['data' => 'x'];

        $block_a = new Block($type_a, $data, $nodes);
        $block_b = $block_a->withType($type_b);

        $this->assertNotSame($block_a, $block_b);
        $this->assertSame($type_a, $block_a->getType());
        $this->assertSame($type_b, $block_b->getType());
        $this->assertSame($nodes, $block_a->getNodes());
        $this->assertSame($nodes, $block_b->getNodes());
        $this->assertSame($data, $block_a->getData());
        $this->assertSame($data, $block_b->getData());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_nodes()
    {
        $type = 'block_a';
        $nodes_a = [new Inline('a')];
        $nodes_b = [new Inline('b')];
        $data = ['data' => 'x'];

        $block_a = new Block($type, $data, $nodes_a);
        $block_b = $block_a->withNodes($nodes_b);

        $this->assertNotSame($block_a, $block_b);
        $this->assertSame($type, $block_a->getType());
        $this->assertSame($type, $block_b->getType());
        $this->assertSame($nodes_a, $block_a->getNodes());
        $this->assertSame($nodes_b, $block_b->getNodes());
        $this->assertSame($data, $block_a->getData());
        $this->assertSame($data, $block_b->getData());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_data()
    {
        $type = 'block_a';
        $nodes = [new Inline('x')];
        $data_a = ['data' => 'a'];
        $data_b = ['data' => 'b'];

        $block_a = new Block($type, $data_a, $nodes);
        $block_b = $block_a->withData($data_b);

        $this->assertNotSame($block_a, $block_b);
        $this->assertSame($type, $block_a->getType());
        $this->assertSame($type, $block_b->getType());
        $this->assertSame($nodes, $block_a->getNodes());
        $this->assertSame($nodes, $block_b->getNodes());
        $this->assertSame($data_a, $block_a->getData());
        $this->assertSame($data_b, $block_b->getData());
    }
}
