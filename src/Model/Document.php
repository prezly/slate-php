<?php

namespace Prezly\Slate\Model;

class Document implements Node
{
    /** @var Block[] */
    private $nodes = [];

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
}