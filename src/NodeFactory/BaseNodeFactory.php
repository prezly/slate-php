<?php

namespace Prezly\Slate\NodeFactory;

use Prezly\Slate\Node;
use Prezly\Slate\Node\LeafNode;
use Prezly\Slate\NodeFactory;

use stdClass;

class BaseNodeFactory implements NodeFactory
{
    public function create(stdClass $object, NodeFactoryStack $stack): Node
    {
        $node = $this->createNode($object);
        foreach ($this->getChildObjects($object) as $child_object) {
            $node->addChild($stack->createNode($child_object));
        }
        return $node;
    }

    private function createNode(stdClass $object): Node
    {
        switch ($object->object) {
            case Node::KIND_LEAF:
                $node = new LeafNode();
                if (! empty($object->text)) {
                    $node->setText($object->text);
                }
                break;
            default:
                $node = new Node();
                $node->setKind($object->object);
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
        switch ($object->object) {
            case Node::KIND_LEAF:
                return $object->marks ?? [];
            case Node::KIND_TEXT:
                return $object->leaves ?? [];
            default:
                return $object->nodes ?? [];
        }
    }
}