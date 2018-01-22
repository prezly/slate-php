<?php

namespace Prezly\Slate\NodeFactory;

use Prezly\Slate\Node;
use Prezly\Slate\NodeFactory;

use stdClass;
use RuntimeException;

class NodeFactoryStack
{
    /** @var NodeFactory[] */
    private $factories = [];

    /**
     * @param NodeFactory[]
     */
    public function __construct(array $factories = [])
    {
        $this->factories = $factories;
    }

    public function push(NodeFactory $factory): NodeFactoryStack
    {
        $this->factories[] = $factory;
        return $this;
    }

    public function createNode(stdClass $object): Node
    {
        if (empty($this->factories)) {
            throw new RuntimeException("Empty node factory stack");
        }
        foreach ($this->factories as $factory) {
            $node = $factory->create($object, $this);
            if (! is_null($node)) {
                return $node;
            }
        }
        throw new RuntimeException("None of the factories returned a node for this object");
    }
}