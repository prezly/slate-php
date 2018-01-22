<?php

namespace Prezly\Slate;

use Prezly\Slate\Node\LeafNode;
use stdClass;

class NodeFactory
{
    public function create(stdClass $object): Node
    {
        $node = $this->createNode($object);
        foreach ($this->getChildObjects($object) as $child_object) {
            $node->addChild($this->create($child_object));
        }
        return $node;
    }

    private function createNode(stdClass $object): Node
    {
        switch ($object->kind) {
            case Node::KIND_LEAF:
                $node = new LeafNode();
                if (! empty($object->text)) {
                    $node->setText($object->text);
                }
                break;
            default:
                $node = new Node();
                $node->setKind($object->kind);
                break;
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