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

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Mark[]
     */
    public function getMarks(): array
    {
        return $this->marks;
    }

    /**\
     * @param Mark $mark
     * @return Leaf current instance (for method chaining)
     */
    public function addMark(Mark $mark): Leaf
    {
        $this->marks[] = $mark;
        return $this;
    }

    /**
     * @param Mark[] $marks
     */
    public function setMarks(array $marks): void
    {
        $this->marks = [];
        foreach ($marks as $mark) {
            $this->addMark($mark);
        }
    }

    /**
     * @param string $text
     * @return Leaf New instance
     */
    public function withText(string $text): Leaf
    {
        return new self($text, $this->marks);
    }

    /**
     * @param Mark[] $marks
     * @return Leaf New instance
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
