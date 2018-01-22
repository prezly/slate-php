<?php

namespace Prezly\Slate;

use InvalidArgumentException;

class Unserializer
{
    /** @var NodeFactory */
    private $factory;

    public function fromJSON(string $json): Node
    {
        $data = json_decode($json, false);
        if (! isset($data->kind) || $data->kind !== Node::KIND_VALUE || ! isset($data->document) || ! is_object($data->document) || $data->document->kind !== Node::KIND_DOCUMENT) {
            throw new InvalidArgumentException("Root node must be a Slate document");
        }

        return $this->getFactory()->create($data->document);
    }

    private function getFactory(): NodeFactory
    {
        if (is_null($this->factory)) {
            $this->factory = new NodeFactory();
        }
        return $this->factory;
    }
}