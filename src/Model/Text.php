<?php

namespace Prezly\Slate\Model;

class Text implements Object
{
    /** @var Leaf[] */
    private $leaves = [];

    /**
     * @return \Prezly\Slate\Model\Leaf[]
     */
    public function getLeaves(): array
    {
        return $this->leaves;
    }

    public function addLeaf(Leaf $leaf): Text
    {
        $this->leaves[] = $leaf;
        return $this;
    }
}
