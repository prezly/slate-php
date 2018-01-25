<?php

namespace Prezly\Slate\Model;

class Document implements Node
{
    /** @var Block[] */
    private $nodes = [];

    /** @var array */
    private $data = [];

    /**
     * @param \Prezly\Slate\Model\Block[] $nodes
     * @param array $data
     */
    public function __construct(array $nodes = [], array $data = [])
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
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

    public function addNode(Block $block): Document
    {
        $this->nodes[] = $block;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
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
        return (object)[
            'object' => Entity::DOCUMENT,
            'data'   => (object)$this->data,
            'nodes'  => array_map(function (Entity $node) {
                return $node->jsonSerialize();
            }, $this->nodes),
        ];
    }
}
