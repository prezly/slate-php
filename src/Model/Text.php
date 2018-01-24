<?php

namespace Prezly\Slate\Model;

class Text implements Object
{
    /** @var Leaf[] */
    private $leaves = [];

    /**
     * @param \Prezly\Slate\Model\Leaf[] $leaves
     */
    public function __construct(array $leaves = [])
    {
        foreach ($leaves as $leaf) {
            $this->addLeaf($leaf);
        }
    }

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
