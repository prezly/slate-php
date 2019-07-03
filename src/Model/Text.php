<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

class Text implements Entity
{
    /** @var string */
    private $text;

    /** @var Mark[] */
    private $marks = [];

    /**
     * @param string $text
     * @param Mark[] $marks
     */
    public function __construct(string $text = '', array $marks = [])
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
        $this->marks = array_values($marks);
    }

    /**
     * @return string
     */
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
     * @return Text New instance
     */
    public function withText(string $text): Text
    {
        return new self($text, $this->marks);
    }

    /**
     * @param Mark[] $marks
     * @return Text New instance
     */
    public function withMarks(array $marks): Text
    {
        return new self($this->text, $marks);
    }
}
