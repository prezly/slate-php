<?php
namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;

class LeafTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_immutably_set_text()
    {
        $text_a = 'aaa aa aa';
        $text_b = 'bbb bb bb';
        $marks = [new Mark('x')];

        $leaf_a = new Leaf($text_a, $marks);
        $leaf_b = $leaf_a->withText($text_b);

        $this->assertNotSame($leaf_a, $leaf_b);
        $this->assertSame($text_a, $leaf_a->getText());
        $this->assertSame($text_b, $leaf_b->getText());
        $this->assertSame($marks, $leaf_a->getMarks());
        $this->assertSame($marks, $leaf_b->getMarks());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_marks()
    {
        $text = 'xxx xx xx';
        $marks_a = [new Mark('a')];
        $marks_b = [new Mark('b')];

        $leaf_a = new Leaf($text, $marks_a);
        $leaf_b = $leaf_a->withMarks($marks_b);

        $this->assertNotSame($leaf_a, $leaf_b);
        $this->assertSame($text, $leaf_a->getText());
        $this->assertSame($text, $leaf_b->getText());
        $this->assertSame($marks_a, $leaf_a->getMarks());
        $this->assertSame($marks_b, $leaf_b->getMarks());
    }
}
