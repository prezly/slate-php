<?php

namespace Prezly\Slate\Model;

abstract class Element extends Node
{
    /** @var Node[] */
    private array $children;

    /**
     * @param  Node[]  $children
     */
    public function __construct(array $children)
    {
        parent::__construct();

        $this->initChildren($children);
    }

    /**
     * @return Node[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param  Node[]  $children
     * @return $this
     */
    public function withChildren(array $children): self
    {
        $that = clone $this;
        $that->initChildren($children);

        return $that;
    }

    /**
     * @param  Node[]  $children
     */
    private function initChildren(array $children): void
    {
        $children = (function (Node ...$children) {
            return $children;
        })(...$children);

        $this->children = $children;
    }
}
