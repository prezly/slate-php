<?php
namespace Prezly\Slate\Tests\Serialization\Versions\v0_40;

use InvalidArgumentException;
use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Versions\v0_40_EntitySerializer;
use Prezly\Slate\Tests\TestCase;
use stdClass;

/**
 * @covers \Prezly\Slate\Serialization\Versions\v0_40_EntitySerializer
 */
class v0_40_EntitySerializerTest extends TestCase
{
    private function serializer(): v0_40_EntitySerializer
    {
        return new v0_40_EntitySerializer();
    }

    /**
     * @test
     * @dataProvider values
     * @param \Prezly\Slate\Model\Value $value
     * @param \stdClass $serialized
     */
    public function it_should_serialize_values(Value $value, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeValue($value)
        );
    }

    /**
     * @test
     * @dataProvider values
     * @param \Prezly\Slate\Model\Value $value
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_values(Value $value, stdClass $serialized): void
    {
        $this->assertEquals(
            $value,
            $this->serializer()->unserializeValue($serialized)
        );
    }

    /**
     * @test
     * @dataProvider invalid_values
     * @param \stdClass $invalid_value
     */
    public function it_should_throw_on_unserializing_invalid_values(stdClass $invalid_value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeValue($invalid_value);
    }

    public function marks(): iterable
    {
        yield 'Mark(bold)' => [
            new Mark('bold'),
            (object) ['object' => 'mark', 'type' => 'bold', 'data' => (object) []],
        ];

        $color_mark = new Mark('color', ['color' => 'red']);
        yield 'Mark(color)' => [
            $color_mark,
            (object) ['object' => 'mark', 'type' => 'color', 'data' => (object) ['color' => 'red']],
        ];
    }

    public function leaves(): iterable
    {
        $hello_leaf = new Leaf('hello');
        yield 'Leaf(hello)' => [
            $hello_leaf,
            (object) ['object' => 'leaf', 'text' => 'hello', 'marks' => []],
        ];

        foreach ($this->marks() as $name => [$mark, $serialized_mark]) {
            yield "Leaf(hello, [{$name}])" => [
                new Leaf('hello', [$mark]),
                (object) ['object' => 'leaf', 'text' => 'hello', 'marks' => [$serialized_mark]],
            ];
        }
    }

    public function texts(): iterable
    {
        $empty_text = new Text();
        yield 'Text()' => [
            $empty_text,
            (object) ['object' => 'text', 'leaves' => []],
        ];

        foreach ($this->leaves() as $name => [$leaf, $serialized_leaf]) {
            yield "Text([{$name}])" => [
                new Text([$leaf]),
                (object) ['object' => 'text', 'leaves' => [$serialized_leaf]],
            ];
        }

        foreach ($this->aggregate($this->leaves()) as $names => [$leaves, $serialized_leaves]) {
            yield "Text([{$names}])" => [
                new Text($leaves),
                (object) ['object' => 'text', 'leaves' => $serialized_leaves],
            ];
        }
    }

    public function inlines(): iterable
    {
        $empty_mention = new Inline('mention', [], ['username' => 'Elvis']);
        yield 'Inline(mention, [], { username: Elvis })' => [
            $empty_mention,
            (object) [
                'object' => 'inline',
                'type'   => 'mention',
                'nodes'  => [],
                'data'   => (object) ['username' => 'Elvis'],
            ],
        ];

        foreach ($this->texts() as $name => [$text, $serialized_text]) {
            yield "Inline(mention, [{$name}])" => [
                new Inline('mention', [$text]),
                (object) [
                    'object' => 'inline',
                    'type'   => 'mention',
                    'nodes'  => [$serialized_text],
                    'data'   => (object) [],
                ],
            ];
        }

        foreach ($this->aggregate($this->texts()) as $names => [$texts, $serialized_texts]) {
            yield "Inline(mention, [{$names}], { username: Elvis })" => [
                new Inline('mention', $texts, ['username' => 'Elvis']),
                (object) [
                    'object' => 'inline',
                    'type'   => 'mention',
                    'nodes'  => $serialized_texts,
                    'data'   => (object) ['username' => 'Elvis'],
                ],
            ];
        }
    }

    public function blocks(): iterable
    {
        $empty_paragraph = new Block('paragraph');
        yield 'Block(paragraph)' => [
            $empty_paragraph,
            (object) [
                'object' => 'block',
                'type'   => 'paragraph',
                'nodes'  => [],
                'data'   => (object) [],
            ],
        ];


        foreach ($this->texts() as $name => [$text, $serialized_text]) {
            yield "Block(paragraph, [{$name}])" => [
                new Block('paragraph', [$text]),
                (object) [
                    'object' => 'block',
                    'type'   => 'paragraph',
                    'nodes'  => [$serialized_text],
                    'data'   => (object) [],
                ],
            ];
        }

        foreach ($this->aggregate($this->texts()) as $names => [$texts, $serialized_texts]) {
            yield "Block(quote, [{$names}], { author: Elvis })" => [
                new Block('quote', $texts, ['author' => 'Elvis']),
                (object) [
                    'object' => 'block',
                    'type'   => 'quote',
                    'nodes'  => $serialized_texts,
                    'data'   => (object) ['author' => 'Elvis'],
                ],
            ];
        }
    }

    public function documents(): iterable
    {
        $empty_document = new Document();
        yield 'Document()' => [
            $empty_document,
            (object) [
                'object' => 'document',
                'nodes'  => [],
                'data'   => (object) [],
            ],
        ];

        foreach ($this->blocks() as $name => [$block, $serialized_block]) {
            yield "Document([{$name}])" => [
                new Document([$block]),
                (object) [
                    'object' => 'document',
                    'nodes'  => [$serialized_block],
                    'data'   => (object) [],
                ],
            ];
        }

        foreach ($this->aggregate($this->blocks()) as $names => [$blocks, $serialized_blocks]) {
            yield "Document([{$names}], { year: 2019 })" => [
                new Document($blocks, ['year' => '2019']),
                (object) [
                    'object' => 'document',
                    'nodes'  => $serialized_blocks,
                    'data'   => (object) ['year' => 2019],
                ],
            ];
        }
    }

    public function values(): iterable
    {
        foreach ($this->documents() as $name => [$document, $serialized_document]) {
            yield "Value({$name})" => [
                new Value($document),
                (object) [
                    'object'   => 'value',
                    'document' => $serialized_document,
                ],
            ];
        }

        yield "Return to Sender" => [
            require __DIR__ . '/fixtures/return-to-sender.value.php',
            require __DIR__ . '/fixtures/return-to-sender.std-class.php',
        ];
    }

    public function invalid_marks(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a mark' => [(object) ['object' => 'block', 'type' => 'paragraph']];
        yield 'no type' => [(object) ['object' => 'mark', 'data' => (object) []]];
        yield 'no data' => [(object) ['object' => 'mark', 'type' => 'bold']];
        yield 'invalid data' => [(object) ['object' => 'mark', 'data' => []]];
    }

    public function invalid_leaves(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a leaf' => [(object) ['object' => 'block', 'type' => 'paragraph']];
        yield 'no text' => [(object) ['object' => 'leaf']];
        yield 'invalid text' => [(object) ['object' => 'leaf', 'text' => 2]];
    }

    public function invalid_texts(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a text' => [(object) ['object' => 'block', 'type' => 'paragraph']];
        yield 'no leaves' => [(object) ['object' => 'text']];
        yield 'invalid leaves' => [(object) ['object' => 'text', 'leaves' => 2]];
    }

    public function invalid_inlines(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not an inline' => [(object) ['object' => 'block', 'type' => 'paragraph']];
        yield 'no type' => [(object) ['object' => 'inline', 'nodes' => [], 'data' => (object) []]];
        yield 'invalid type' => [(object) ['object' => 'inline', 'type' => 10, 'data' => (object) []]];
        yield 'no nodes' => [(object) ['object' => 'inline', 'type' => 'mention', 'data' => (object) []]];
        yield 'invalid nodes' => [(object) ['object' => 'inline', 'type' => 'mention', 'nodes' => false]];
        yield 'no data' => [(object) ['object' => 'inline', 'type' => 'mention', 'nodes' => []]];
        yield 'invalid data' => [(object) ['object' => 'inline', 'type' => 'mention', 'data' => null]];
    }

    public function invalid_blocks(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a block' => [(object) ['object' => 'inline', 'type' => 'mention']];
        yield 'no type' => [(object) ['object' => 'block', 'nodes' => [], 'data' => (object) []]];
        yield 'invalid type' => [(object) ['object' => 'block', 'type' => 10, 'data' => (object) []]];
        yield 'no nodes' => [(object) ['object' => 'block', 'type' => 'paragraph', 'data' => (object) []]];
        yield 'invalid nodes' => [(object) ['object' => 'block', 'type' => 'paragraph', 'nodes' => false]];
        yield 'no data' => [(object) ['object' => 'block', 'type' => 'paragraph', 'nodes' => []]];
        yield 'invalid data' => [(object) ['object' => 'block', 'type' => 'paragraph', 'data' => null]];
    }

    public function invalid_documents(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a document' => [(object) ['object' => 'inline', 'type' => 'mention']];
        yield 'no nodes' => [(object) ['object' => 'document', 'data' => (object) []]];
        yield 'invalid nodes' => [(object) ['object' => 'document', 'nodes' => false]];
        yield 'no data' => [(object) ['object' => 'document', 'nodes' => []]];
        yield 'invalid data' => [(object) ['object' => 'document', 'data' => null]];
    }

    public function invalid_values(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a document' => [(object) ['object' => 'inline', 'type' => 'mention']];
        yield 'no nodes' => [(object) ['object' => 'document', 'data' => (object) []]];
        yield 'invalid nodes' => [(object) ['object' => 'document', 'nodes' => false]];
        yield 'no data' => [(object) ['object' => 'document', 'nodes' => []]];
        yield 'invalid data' => [(object) ['object' => 'document', 'data' => null]];
    }

    public function invalid_entities(): iterable
    {
        yield from $this->invalid_marks();
        yield from $this->invalid_leaves();
        yield from $this->invalid_texts();
        yield from $this->invalid_inlines();
        yield from $this->invalid_blocks();
        yield from $this->invalid_documents();
        yield from $this->invalid_values();
    }

    /**
     * Aggregate separate entities datasets into a combined data set.
     *
     * @param iterable $datasets
     * @return array[]
     */
    private function aggregate(iterable $datasets): iterable
    {
        $names = [];
        $entities = [];
        $serialized_entities = [];

        foreach ($datasets as $name => [$entity, $serialized_entity]) {
            $names[] = $name;
            $entities[] = $entity;
            $serialized_entities[] = $serialized_entity;
        }

        return [
            implode(',', $names) => [$entities, $serialized_entities],
        ];
    }
}
