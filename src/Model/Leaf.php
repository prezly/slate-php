<?php

namespace Prezly\Slate\Model;

class Leaf implements Entity
{
    /** @var string */
    private $text;

    /** @var Mark[] */
    private $marks = [];

    /**
     * @param string $text
     * @param \Prezly\Slate\Model\Mark[] $marks
     */
    public function __construct(string $text, array $marks = [])
    {
        $this->text = $text;
        foreach ($marks as $mark) {
            $this->addMark($mark);
        }
    }

    public function getText(): ?string
    {
        return $this->text;
    }

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

    public function addMark(Mark $mark): Leaf
    {
        $this->marks[] = $mark;
        return $this;
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
