<?php
namespace Prezly\Slate\Tests\Model;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Tests\TestCase;

class InlineTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_immutably_set_type()
    {
        $type_a = 'inline_a';
        $type_b = 'inline_b';
        $nodes = [new Block('x')];
        $data = ['data' => 'x'];

        $inline_a = new Inline($type_a, $nodes, $data);
        $inline_b = $inline_a->withType($type_b);

        $this->assertNotSame($inline_a, $inline_b);
        $this->assertSame($type_a, $inline_a->getType());
        $this->assertSame($type_b, $inline_b->getType());
        $this->assertSame($nodes, $inline_a->getNodes());
        $this->assertSame($nodes, $inline_b->getNodes());
        $this->assertSame($data, $inline_a->getData());
        $this->assertSame($data, $inline_b->getData());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_nodes()
    {
        $type = 'inline_a';
        $nodes_a = [new Block('a')];
        $nodes_b = [new Block('b')];
        $data = ['data' => 'x'];

        $inline_a = new Inline($type, $nodes_a, $data);
        $inline_b = $inline_a->withNodes($nodes_b);

        $this->assertNotSame($inline_a, $inline_b);
        $this->assertSame($type, $inline_a->getType());
        $this->assertSame($type, $inline_b->getType());
        $this->assertSame($nodes_a, $inline_a->getNodes());
        $this->assertSame($nodes_b, $inline_b->getNodes());
        $this->assertSame($data, $inline_a->getData());
        $this->assertSame($data, $inline_b->getData());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_data()
    {
        $type = 'inline_a';
        $nodes = [new Block('x')];
        $data_a = ['data' => 'a'];
        $data_b = ['data' => 'b'];

        $inline_a = new Inline($type, $nodes, $data_a);
        $inline_b = $inline_a->withData($data_b);

        $this->assertNotSame($inline_a, $inline_b);
        $this->assertSame($type, $inline_a->getType());
        $this->assertSame($type, $inline_b->getType());
        $this->assertSame($nodes, $inline_a->getNodes());
        $this->assertSame($nodes, $inline_b->getNodes());
        $this->assertSame($data_a, $inline_a->getData());
        $this->assertSame($data_b, $inline_b->getData());
    }
}
