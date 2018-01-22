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
    private $kind;

    /** @var Node[] */
    private $chidren = [];

    /**
     * All Slate nodes have a `kind` property, so we enforce this by requiring it in the constructor
     *
     * @param string $kind
     */
    public function __construct(string $kind)
    {
        $this->kind = $kind;
    }

    public function getKind(): string
    {
        return $this->kind;
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