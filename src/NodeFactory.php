<?php

namespace Prezly\Slate;

use Prezly\Slate\NodeFactory\NodeFactoryStack;
use stdClass;

interface NodeFactory
{
    /**
     * Each factory should return a Node instance or null, in which case the next factory in the stack
     * will be called. Every factory is expected to call the stack when creating child nodes, to make sure
     * that each node, no matter how deep, passes through the entire factory stack
     *
     * @param stdClass $object
     * @param NodeFactoryStack $stack
     * @return Node|null
     */
    public function create(stdClass $object, NodeFactoryStack $stack): ?Node;
}