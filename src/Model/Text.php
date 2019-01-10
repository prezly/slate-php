<?php

namespace Prezly\Slate\Model;

class Text extends TextContainingNode
{
    const OBJECT = 'text';

    /** @var Leaf[] */
    public $leaves = [];

    /**
     * @param \Prezly\Slate\Model\Leaf[] $leaves
     * @return void
     */
    public function __construct(array $leaves = [])
    {
        $this->leaves = $leaves;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'leaves'  => $this->leaves
        ];
    }

    /**
     * @param \stdClass $object
     * @return self
     */
    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            array_map(
                function(\stdClass $leaf): Leaf {
                    return Leaf::jsonDeserialize($leaf);
                },
                $object->leaves
            )
        );
    }

    /**
     * Concatenate all the descendant text nodes of this node.
     *
     * @return string
     */
    protected function computeTextProperty(): string
    {

        return array_reduce(
            $this->leaves,
            function(string $text, Leaf $leaf): string {
                return $text . $leaf->text;
            },
            ''
        );
    }
}
