<?php
namespace Prezly\Slate\Tests\Model;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Tests\TestCase;

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

        $block_a = new Block($type_a, $nodes, $data);
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

        $block_a = new Block($type, $nodes_a, $data);
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

        $block_a = new Block($type, $nodes, $data_a);
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
