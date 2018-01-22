<?php

namespace Prezly\Slate;

class Node
{
    const KIND_VALUE = "value";
    const KIND_DOCUMENT = "document";
    const KIND_BLOCK = "block";
    const KIND_INLINE = "inline";
    const KIND_TEXT = "text";
    const KIND_LEAF = "leaf";

    /** @var string */
    protected $kind;

    /** @var Node[] */
    private $chidren = [];

    public function getKind(): string
    {
        return $this->kind;
    }

    public function setKind(string $kind): Node
    {
        $this->kind = $kind;
        return $this;
    }

    public function addChild(Node $node): Node
    {
        $this->chidren[] = $node;
        return $this;
    }

    /**
     * @return Node[]
     */
    public function getChidren(): array
    {
        return $this->chidren;
    }
}