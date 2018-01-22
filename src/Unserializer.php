<?php

namespace Prezly\Slate;

use stdClass;
use InvalidArgumentException;

class Unserializer
{
    public function fromJSON(string $json): Node
    {
        $data = json_decode($json, false);
        if (! isset($data->kind) || $data->kind !== Node::KIND_VALUE || ! isset($data->document) || ! is_object($data->document) || $data->document->kind !== Node::KIND_DOCUMENT) {
            throw new InvalidArgumentException("Root node must be a Slate document");
        }

        return $this->createNode($data->document);
    }

    private function createNode(stdClass $object): Node
    {
        $node = new Node($object->kind);
        foreach ($object->nodes ?? $object->leaves ?? [] as $child_object) {
            $node->addChild($this->createNode($child_object));
        }
        return $node;
    }
}