<?php

namespace Prezly\Slate;

use stdClass;

class NodeFactory
{
    public function create(stdClass $object): Node
    {
        $node = new Node($object->kind);
        foreach ($this->getChildObjects($object) as $child_object) {
            $node->addChild($this->create($child_object));
        }
        return $node;
    }

    /**
     * @param stdClass $object
     * @return stdClass[]
     */
    private function getChildObjects(stdClass $object): array
    {
        switch ($object->kind) {
            case Node::KIND_LEAF:
                return $object->marks ?? [];
            case Node::KIND_TEXT:
                return $object->leaves ?? [];
            default:
                return $object->nodes ?? [];
        }
    }
}