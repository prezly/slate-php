<?php

namespace Prezly\Slate\Model;

class Inline implements Node
{
    /** @var string */
    private $type;

    /** @var array */
    private $data = [];

    /** @var Object[] */
    private $nodes = [];

    /**
     * @param string $type
     * @param array $data
     * @param \Prezly\Slate\Model\Object[] $nodes
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

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return \Prezly\Slate\Model\Object[]
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

    public function getText(): string
    {
        $text = '';
        foreach ($this->nodes as $node) {
            if ($node instanceof Text) {
                $text .= $node->getText();

            } elseif ($node instanceof Node) {
                $text .= $node->getText();

            } else {
                throw new \LogicException('WTF! This should never happen');
            }
        }
        return $text;
    }
}
