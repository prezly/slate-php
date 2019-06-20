<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

/**
 * @deprecated Leaf will be dropped in the next version in favour of Text node.
 * @see https://github.com/ianstormtaylor/slate/blob/master/packages/slate/Changelog.md#0460--may-1-2019
 */
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
}
