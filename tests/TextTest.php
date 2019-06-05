<?php
namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Text;

class TextTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_immutably_set_leaves()
    {
        $leaves_a = [new Leaf('a')];
        $leaves_b = [new Leaf('b')];

        $text_a = new Text($leaves_a);
        $text_b = $text_a->withLeaves($leaves_b);

        $this->assertNotSame($text_a, $text_b);
        $this->assertSame($leaves_a, $text_a->getLeaves());
        $this->assertSame($leaves_b, $text_b->getLeaves());
    }
}
