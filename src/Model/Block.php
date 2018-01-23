<?php

namespace Prezly\Slate\Model;

class Block implements Node
{
    /** @var string */
    private $type;

    /** @var array */
    private $data = [];

    /** @var Node[] */
    private $nodes = [];

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

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
}