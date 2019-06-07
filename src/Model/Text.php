<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

class Text implements Entity
{
    /** @var Leaf[] */
    private $leaves = [];

    /**
     * @param Leaf[] $leaves
     */
    public function __construct(array $leaves = [])
    {
        foreach ($leaves as $leaf) {
            if (! $leaf instanceof Leaf) {
                throw new InvalidArgumentException(sprintf(
                    'Text can only have %s as leaves. %s given.',
                    Leaf::class,
                    is_object($leaf) ? get_class($leaf) : gettype($leaf)
                ));
            }
        }

        $this->leaves = array_values($leaves);
    }

    /**
     * @return Leaf[]
     */
    public function getLeaves(): array
    {
        return $this->leaves;
    }

    /**
     * @param Leaf[] $leaves
     * @return Text New instance
     */
    public function withLeaves(array $leaves): self
    {
        return new self($leaves);
    }

    public function getText(): string
    {
        $text = '';
        foreach ($this->leaves as $leaf) {
            $text .= $leaf->getText();
        }
        return $text;
    }

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::TEXT,
            'leaves' => array_map(function (Leaf $leaf) {
                return $leaf->jsonSerialize();
            }, $this->leaves)
        ];
    }
}
