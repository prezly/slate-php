<?php

namespace Prezly\Slate\Model;

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
        $this->type = $type;
        $this->data = $data;

        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getData(): array
    {
        return $this->data;
    }

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
     * @param Node|Text $node
     * @return Inline
     */
    public function addNode(Entity $node): Inline
    {
        if ($node instanceof Node || $node instanceof Text) {
            $this->nodes[] = $node;
            return $this;
        }

        throw new \InvalidArgumentException('Inline can only have Node and Text child nodes');
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
