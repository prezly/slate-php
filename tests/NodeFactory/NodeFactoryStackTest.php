<?php

namespace Prezly\Slate\Tests\NodeFactory;

use Prezly\Slate\Node;
use Prezly\Slate\NodeFactory;
use Prezly\Slate\NodeFactory\NodeFactoryStack;
use Prezly\Slate\Tests\TestCase;
use RuntimeException;

class NodeFactoryStackTest extends TestCase
{
    /** @var NodeFactoryStack */
    private $stack;

    protected function setUp()
    {
        parent::setUp();
        $this->stack = new NodeFactoryStack();
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_empty()
    {
        $fixture = $this->loadFixture("00_empty_document.json");

        $this->expectException(RuntimeException::class);
        $this->stack->createNode(json_decode($fixture, false));
    }

    /**
     * @test
     */
    public function it_should_call_factories_in_stack()
    {
        $node = $this->createMock(Node::class);

        $fixture = $this->loadFixture("00_empty_document.json");
        $object = json_decode($fixture, false);

        // It should call the next factory in the stack when one returns null
        $foo = $this->createMock(NodeFactory::class);
        $foo->expects($this->once())
            ->method("create")
            ->with($object, $this->stack)
            ->willReturn(null);

        $bar = $this->createMock(NodeFactory::class);
        $bar->expects($this->once())
            ->method("create")
            ->with($object, $this->stack)
            ->willReturn($node);

        // It should not call rest of factories in stack when one already returned a node
        $baz = $this->createMock(NodeFactory::class);
        $baz->expects($this->never())->method("create");

        /** @noinspection PhpParamsInspection */
        $this->stack->push($foo);

        /** @noinspection PhpParamsInspection */
        $this->stack->push($bar);

        /** @noinspection PhpParamsInspection */
        $this->stack->push($baz);

        $this->assertSame($node, $this->stack->createNode($object));
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_all_factories_skip()
    {
        $fixture = $this->loadFixture("00_empty_document.json");
        $object = json_decode($fixture, false);

        $factory = $this->createMock(NodeFactory::class);
        $factory->expects($this->once())->method("create")->willReturn(null);

        /** @noinspection PhpParamsInspection */
        $this->stack->push($factory);

        $this->expectException(RuntimeException::class);
        $this->stack->createNode($object);
    }
}
