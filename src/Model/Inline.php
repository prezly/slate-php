<?php

namespace Prezly\Slate\Model;

class Inline extends TextContainingNode
{
    const OBJECT = 'inline';

    /** @var string */
    public $type;

    /** @var \stdClass */
    public $data;

    /** @var Inline[]|Text[] */
    private $nodes = [];

    /**
     * @param string $type
     * @param Text[]|Inline[] $nodes
     * @param \stdClass $data
     */
    public function __construct(string $type, ?array $nodes = null, ?\stdClass $data = null)
    {
        $this->type = $type;
        $this->nodes = $nodes ?? [new Text()];
        $this->data = $data ?? new \stdClass();
    }

    /**
     * @return string
     */
    public function computeTextProperty(): string
    {
        return array_reduce(
            $this->nodes,
            function(string $text, TextContainingNode $node): string {
                return $text . $node->text;
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
            'type'   => $this->type,
            'data'   => $this->data,
            'nodes'  => $this->nodes
        ];
    }

    /**
     * @param \stdClass $object
     * @return static
     */
    public static function jsonDeserialize(\stdClass $object)
    {
        return new self(
            $object->type,
            array_map(
                function(\stdClass $node): TextContainingNode {
                    switch($node->object){
                        case self::OBJECT:
                            return self::jsonDeserialize($node);
                        case Text::OBJECT:
                            return Text::jsonDeserialize($node);
                        default:
                            throw new \Exception("Found unknown node of kind $node->object when trying to deserialize a Inline");
                    }
                },
                $object->nodes
            ),
            $object->data
        );
    }
}
