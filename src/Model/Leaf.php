<?php

namespace Prezly\Slate\Model;

class Leaf implements Object
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
