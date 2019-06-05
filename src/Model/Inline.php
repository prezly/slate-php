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
        $this->nodes = $nodes;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @deprecated Deprecated in favor of immutable API. Use withType() instead.
     * @see withType()
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @deprecated Deprecated in favor of immutable API. Use withData() instead.
     * @see withData()
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return Node[]|Text[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @deprecated Deprecated in favor of immutable API. Use withNodes() instead.
     * @see withNodes()
     * @param Node|Text $node
     * @return Inline current instance (for method chaining)
     */
    public function addNode(Entity $node): Inline
    {
        if ($node instanceof Node || $node instanceof Text) {
            $this->nodes[] = $node;
            return $this;
        }

        throw new \InvalidArgumentException('Inline can only have Node and Text child nodes');
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

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::INLINE,
            'type'   => $this->type,
            'data'   => (object) $this->data,
            'nodes'  => array_map(function (Entity $node) {
                return $node->jsonSerialize();
            }, $this->nodes)
        ];
    }
}
