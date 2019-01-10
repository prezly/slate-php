<?php

namespace Prezly\Slate\Model;

class Document extends TextContainingNode
{
    const OBJECT = 'document';

    /** @var Block[] */
    public $nodes;

    /** @var \stdClass */
    public $data;

    /**
     * @param \Prezly\Slate\Model\Block[] $nodes
     * @param \stdClass|null $data
     * @return void
     */
    public function __construct(array $nodes = [], ?\stdClass $data = null)
    {
        $this->nodes = $nodes;
        $this->data = $data ?? new \stdClass();
    }

    /**
     * @return string
     */
    protected function computeTextProperty(): string
    {
        return array_reduce(
            $this->nodes,
            function(string $text, Block $block): string {
                return $text . $block->text;
            },
            ''
        );
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'data'   => $this->data,
            'nodes'  => $this->nodes,
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
                function(\stdClass $block): Block {
                    return Block::jsonDeserialize($block);
                },
                $object->nodes
            ),
            $object->data
        );
    }
}
