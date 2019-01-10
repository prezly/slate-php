<?php

namespace Prezly\Slate\Model;

class Leaf extends TextContainingNode
{
    const OBJECT = 'leaf';

    /** @var string */
    public $text;

    /** @var Mark[] */
    public $marks = [];

    /**
     * @param string $text
     * @param \Prezly\Slate\Model\Mark[] $marks
     */
    public function __construct(string $text, array $marks = [])
    {
        $this->text = $text;
        $this->marks = $marks;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'text'   => $this->text,
            'marks'  => $this->marks
        ];
    }

    /**
     * @param \stdClass $object
     * @return self
     */
    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            $object->text,
            array_map(
                function(\stdClass $mark): Mark {
                    return Mark::jsonDeserialize($mark);
                },
                $object->marks
            )
        );
    }

    /**
     * We are finally there, just return the text.
     *
     * @return string
     */
    protected function computeTextProperty(): string
    {
        return $this->text;
    }
}
