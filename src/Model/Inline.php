<?php

namespace Prezly\Slate\Model;

class Inline implements Node
{
    /** @var string */
    private $type;

    /** @var array */
    private $data = [];

    /** @var Node[] */
    private $nodes = [];

    /**
     * @param string $type
     * @param array $data
     * @param \Prezly\Slate\Model\Node[] $nodes
     */
    public function __construct(string $type, array $data = [], array $nodes = [])
    {
        $this->type = $type;
        $this->data = $data;
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }


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

    public function addNode(Object $node): Inline
    {
        $this->nodes[] = $node;
        return $this;
    }
}
