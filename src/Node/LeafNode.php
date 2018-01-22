<?php

namespace Prezly\Slate\Node;

use Prezly\Slate\Node;

class LeafNode extends Node
{
    /** @var string */
    protected $kind = Node::KIND_LEAF;

    /** @var string */
    private $text;

    public function getMarks(): array
    {
        return $this->getChildren();
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }
}