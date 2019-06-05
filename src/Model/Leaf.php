<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

class Leaf implements Entity
{
    /** @var string */
    private $text;

    /** @var Mark[] */
    private $marks = [];

    /**
     * @param string $text
     * @param Mark[] $marks
     */
    public function __construct(string $text, array $marks = [])
    {
        foreach ($marks as $mark) {
            if (! $mark instanceof Mark) {
                throw new InvalidArgumentException(sprintf(
                    'Leaf can only have %s as child marks. %s given.',
                    Mark::class,
                    is_object($mark) ? get_class($mark) : gettype($mark)
                ));
            }
        }

        $this->text = $text;
        $this->marks = $marks;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Mark[]
     */
    public function getMarks(): array
    {
        return $this->marks;
    }

    /**
     * @param string $text
     * @return Leaf new instance
     */
    public function withText(string $text): Leaf
    {
        return new self($text, $this->marks);
    }

    /**
     * @param Mark[] $marks
     * @return Leaf new instance
     */
    public function withMarks(array $marks): Leaf
    {
        return new self($this->text, $marks);
    }

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::LEAF,
            'text'   => $this->text,
            'marks'  => array_map(function (Mark $mark) {
                return $mark->jsonSerialize();
            }, $this->marks)
        ];
    }
}
