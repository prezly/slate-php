<?php

namespace Prezly\Slate\Model;

class Leaf implements Object
{
    /** @var string */
    private $text;

    /** @var Mark[] */
    private $marks = [];

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text)
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
}