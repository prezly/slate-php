<?php

namespace Prezly\Slate\Model;

class Document implements Node
{
    /** @var Block[] */
    private $nodes = [];

    /**
     * @param \Prezly\Slate\Model\Block[] $nodes
     */
    public function __construct(array $nodes = [])
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
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
            'nodes'  => array_map(function (Entity $node) {
                return $node->jsonSerialize();
            }, $this->nodes)
        ];
    }
}
