<?php

namespace Prezly\Slate\Tests\Model;

use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Tests\TestCase;

class ValueTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_serialize_to_json()
    {
        $value = new Value(new Document());

        $this->assertJson($value->toJson());
        $this->assertJson($value->toJson(JSON_PRETTY_PRINT));
        $this->assertNotEquals($value->toJson(), $value->toJson(JSON_PRETTY_PRINT));
    }

    /**
     * @test
     */
    public function it_should_immutably_set_document()
    {
        $document_a = new Document();
        $document_b = new Document();

        $value_a = new Value($document_a);
        $value_b = $value_a->withDocument($document_b);

        $this->assertNotSame($value_a, $value_b);
        $this->assertSame($document_a, $value_a->getDocument());
        $this->assertSame($document_b, $value_b->getDocument());
    }
}
