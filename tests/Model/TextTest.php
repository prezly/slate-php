<?php
namespace Prezly\Slate\Tests\Model;

use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Tests\TestCase;

class TextTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_immutably_set_text()
    {
        $marks = [new Mark('bold')];

        $text_a = new Text('a', $marks);
        $text_b = $text_a->withText('b');

        $this->assertNotSame($text_a, $text_b);

        $this->assertSame('a', $text_a->getText());
        $this->assertSame('b', $text_b->getText());

        $this->assertSame($marks, $text_a->getMarks());
        $this->assertSame($marks, $text_b->getMarks());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_marks()
    {
        $marks_a = [new Mark('bold')];
        $marks_b = [new Mark('italic')];

        $text_a = new Text('a', $marks_a);
        $text_b = $text_a->withMarks($marks_b);

        $this->assertNotSame($text_a, $text_b);

        $this->assertSame('a', $text_a->getText());
        $this->assertSame('a', $text_b->getText());

        $this->assertSame($marks_a, $text_a->getMarks());
        $this->assertSame($marks_b, $text_b->getMarks());
    }
}
