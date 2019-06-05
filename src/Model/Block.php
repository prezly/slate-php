<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

class Block implements Node
{
    /** @var string */
    private $type;

    /** @var array */
    private $data = [];

    /** @var Node[]|Text[] */
    private $nodes = [];

    /**
     * @param string $type
     * @param array $data
     * @param Node[]|Text[] $nodes
     */
    public function __construct(string $type, array $data = [], array $nodes = [])
    {
        foreach ($nodes as $node) {
            if (! $node instanceof Node && ! $node instanceof Text) {
                throw new InvalidArgumentException(sprintf(
                    'Block can only have %s or %s as child nodes. %s given.',
                    Node::class,
                    Text::class,
                    is_object($node) ? get_class($node) : gettype($node)
                ));
            }
        }

        $this->type = $type;
        $this->data = $data;
        $this->nodes = array_values($nodes);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Node[]|Text[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        $text = '';
        foreach ($this->nodes as $node) {
            $text .= $node->getText();
        }
        return $text;
    }

    /**
     * @param string $type
     * @return Block new instance
     */
    public function withType(string $type): Block
    {
        return new self($type, $this->data, $this->nodes);
    }

    /**
     * @param array $data
     * @return Block new instance
     */
    public function withData(array $data): Block
    {
        return new self($this->type, $data, $this->nodes);
    }

    /**
     * @param Node[]|Text[] $nodes
     * @return Block new instance
     */
    public function withNodes(array $nodes): Block
    {
        return new self($this->type, $this->data, $nodes);
    }

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::BLOCK,
            'type'   => $this->type,
            'data'   => (object) $this->data,
            'nodes'  => array_map(function (Entity $node) {
                return $node->jsonSerialize();
            }, $this->nodes)
        ];
    }
}
