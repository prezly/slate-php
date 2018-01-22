<?php

namespace Prezly\Slate;

use stdClass;

class NodeFactory
{
    public function create(stdClass $object): Node
    {
        $node = new Node($object->kind);
        foreach ($object->nodes ?? $object->leaves ?? [] as $child_object) {
            $node->addChild($this->create($child_object));
        }
        return $node;
    }
}