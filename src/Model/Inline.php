<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

class Inline implements Node
{
    /** @var string */
    private $type;

    /** @var array */
    private $data = [];

    /** @var Node[]|Text[] */
    private $nodes = [];

    /**
     * @param string $type
     * @param Node[]|Text[] $nodes
     * @param array $data
     */
    public function __construct(string $type, array $nodes = [], array $data = [])
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
     * @param string $type
     * @return Inline New instance
     */
    public function withType(string $type): self
    {
        return new self($type, $this->nodes, $this->data);
    }

    /**
     * @param array $data
     * @return Inline New instance
     */
    public function withData(array $data): self
    {
        return new self($this->type, $this->nodes, $data);
    }

    /**
     * @param Node[]|Text[] $nodes
     * @return Inline New instance
     */
    public function withNodes(array $nodes): Inline
    {
        return new self($this->type, $nodes, $this->data);
    }

    public function getText(): string
    {
        $text = '';
        foreach ($this->nodes as $node) {
            $text .= $node->getText();
        }
        return $text;
    }
}
