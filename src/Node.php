<?php

namespace Prezly\Slate;

class Node
{
    /** @var Node[] */
    private $chidren = [];

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