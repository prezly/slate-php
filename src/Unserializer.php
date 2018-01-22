<?php

namespace Prezly\Slate;

use Prezly\Slate\NodeFactory\BaseNodeFactory;
use Prezly\Slate\NodeFactory\NodeFactoryStack;

use InvalidArgumentException;

class Unserializer
{
    /** @var NodeFactoryStack[] */
    private $factory_stack;

    public function __construct(NodeFactoryStack $node_factory_stack = null)
    {
        if (is_null($node_factory_stack)) {
            $node_factory_stack = new NodeFactoryStack([
                new BaseNodeFactory(),
            ]);
        }
        $this->factory_stack = $node_factory_stack;
    }

    public function fromJSON(string $json): Node
    {
        $data = json_decode($json, false);

        // TODO: Switch to JSON schema for validation
        if (! isset($data->object) || $data->object !== Node::KIND_VALUE || ! isset($data->document) || ! is_object($data->document) || $data->document->object !== Node::KIND_DOCUMENT) {
            throw new InvalidArgumentException("Root node must be a Slate document");
        }

        return $this->factory_stack->createNode($data->document);
    }
}