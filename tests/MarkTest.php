<?php
namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Mark;

class MarkTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_immutably_set_type()
    {
        $type_a = 'mark_a';
        $type_b = 'mark_b';
        $data = ['data' => 'x'];

        $mark_a = new Mark($type_a, $data);
        $mark_b = $mark_a->withType($type_b);

        $this->assertNotSame($mark_a, $mark_b);
        $this->assertSame($type_a, $mark_a->getType());
        $this->assertSame($type_b, $mark_b->getType());
        $this->assertSame($data, $mark_a->getData());
        $this->assertSame($data, $mark_b->getData());
    }

    /**
     * @test
     */
    public function it_should_immutably_set_data()
    {
        $type = 'mark_a';
        $data_a = ['data' => 'a'];
        $data_b = ['data' => 'b'];

        $mark_a = new Mark($type, $data_a);
        $mark_b = $mark_a->withData($data_b);

        $this->assertNotSame($mark_a, $mark_b);
        $this->assertSame($type, $mark_a->getType());
        $this->assertSame($type, $mark_b->getType());
        $this->assertSame($data_a, $mark_a->getData());
        $this->assertSame($data_b, $mark_b->getData());
    }
}
