<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Unserializer;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class UnserializerTest extends TestCase
{
    /** @var Unserializer */
    private $unserializer;

    protected function setUp()
    {
        parent::setUp();
        $this->unserializer = new Unserializer();
    }

    /**
     * The top level node of the Slate model is the Document. Any JSON that doesn't
     * have a top-level document node is invalid
     *
     * @see https://docs.slatejs.org/slate-core/document
     * @test
     */
    public function it_should_expect_top_level_document()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->unserializer->fromJSON("{}");
    }
}
