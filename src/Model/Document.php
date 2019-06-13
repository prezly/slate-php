<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

class Document implements Node
{
    /** @var Block[] */
    private $nodes = [];

    /** @var array */
    private $data = [];

    /**
     * @param Block[] $nodes
     * @param array $data
     */
    public function __construct(array $nodes = [], array $data = [])
    {
        foreach ($nodes as $node) {
            if (! $node instanceof Block) {
                throw new InvalidArgumentException(sprintf(
                    'Document can only have %s as child nodes. %s given.',
                    Block::class,
                    is_object($node) ? get_class($node) : gettype($node)
                ));
            }
        }
        $this->nodes = array_values($nodes);
        $this->data = $data;
    }

    /**
     * The direct descendants of the Document node can only be Blocks
     *
     * @return Block[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param Block[] $nodes
     * @return Document New Document instance
     */
    public function withNodes(array $nodes): Document
    {
        return new self($nodes, $this->data);
    }

    /**
     * @param array $data
     * @return Document New Document instance
     */
    public function withData(array $data): Document
    {
        return new self($this->nodes, $data);
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

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::DOCUMENT,
            'data'   => (object) $this->data,
            'nodes'  => array_map(function (Entity $node) {
                return $node->jsonSerialize();
            }, $this->nodes),
        ];
    }
}
