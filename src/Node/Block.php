<?php

namespace Prezly\Slate\Node;

use Prezly\Slate\Node;
use Prezly\Slate\Object;

class Block implements Node
{
    /** @var Node[] */
    private $nodes = [];

    /** @var array */
    private $data = [];

    /** @var string */
    private $type;

    /**
     * @return Node[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function addNode(Object $node): Block
    {
        $this->nodes[] = $node;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }
}