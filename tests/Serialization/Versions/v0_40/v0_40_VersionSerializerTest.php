<?php
namespace Prezly\Slate\Tests\Serialization\Versions\v0_40;

use InvalidArgumentException;
use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Versions\v0_40_VersionSerializer;
use Prezly\Slate\Tests\TestCase;
use stdClass;

/**
 * @covers \Prezly\Slate\Serialization\Versions\v0_40_VersionSerializer
 */
class v0_40_VersionSerializerTest extends TestCase
{
    private function serializer(): v0_40_VersionSerializer
    {
        return new v0_40_VersionSerializer();
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

        yield 'Mark(color)' => [
            new Mark('color', ['color' => 'red']),
            (object) ['object' => 'mark', 'type' => 'color', 'data' => (object) ['color' => 'red']],
        ];
    }

    public function texts(): iterable
    {
        yield 'Text()' => [
            new Text(),
            (object) ['object' => 'text', 'leaves' => []],
        ];

        foreach ($this->marks() as $name => [$mark, $serialized_mark]) {
            yield "Text(hello, [$name])" => [
                new Text('hello', [$mark]),
                (object) [
                    'object' => 'text',
                    'leaves' => [
                        (object) ['object' => 'leaf', 'text' => 'hello', 'marks' => [$serialized_mark]],
                    ],
                ],
            ];
        }

        foreach ($this->aggregate($this->marks()) as $names => [$marks, $serialized_marks]) {
            yield "Text('hello all', [{$names}])" => [
                new Text('hello all', $marks),
                (object) [
                    'object' => 'text',
                    'leaves' => [
                        (object) ['object' => 'leaf', 'text' => 'hello all', 'marks' => $serialized_marks],
                    ],
                ],
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

        foreach ($this->invalid_marks() as $name => $invalid_mark) {
            yield "valid leaf, invalid mark ({$name})" => [
                (object) ['object' => 'leaf', 'text' => 'hello', 'marks' => [$invalid_mark]],
            ];
        }
    }

    public function invalid_texts(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a text' => [(object) ['object' => 'block', 'type' => 'paragraph']];
        yield 'no leaves' => [(object) ['object' => 'text']];
        yield 'invalid leaves' => [(object) ['object' => 'text', 'leaves' => 2]];

        foreach ($this->invalid_leaves() as $name => $invalid_leaf) {
            yield "valid text, invalid leaf ({$name})" => [
                (object) ['object' => 'text', 'leaves' => [$invalid_leaf]],
            ];
        }
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

        foreach ($this->invalid_texts() as $name => $invalid_text) {
            yield "valid inline, invalid text ({$name})" => [
                (object) [
                    'object' => 'inline',
                    'type'   => 'mention',
                    'data'   => (object) ['username' => 'elvis'],
                    'nodes'  => [$invalid_text],
                ],
            ];
        }
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

        foreach ($this->invalid_inlines() as $name => $invalid_inline) {
            yield "valid block, invalid inline ({$name})" => [
                (object) [
                    'object' => 'block',
                    'type'   => 'paragraph',
                    'data'   => (object) [],
                    'nodes'  => [$invalid_inline],
                ],
            ];
        }

        foreach ($this->invalid_texts() as $name => $invalid_text) {
            yield "valid block, invalid text ({$name})" => [
                (object) [
                    'object' => 'block',
                    'type'   => 'paragraph',
                    'data'   => (object) [],
                    'nodes'  => [$invalid_text],
                ],
            ];
        }
    }

    public function invalid_documents(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a document' => [(object) ['object' => 'inline', 'type' => 'mention']];
        yield 'no nodes' => [(object) ['object' => 'document', 'data' => (object) []]];
        yield 'invalid nodes' => [(object) ['object' => 'document', 'nodes' => false]];
        yield 'no data' => [(object) ['object' => 'document', 'nodes' => []]];
        yield 'invalid data' => [(object) ['object' => 'document', 'data' => null]];

        foreach ($this->invalid_blocks() as $name => $invalid_block) {
            yield "valid document, invalid block ({$name})" => [
                (object) ['object' => 'document', 'data' => (object) [], 'nodes' => [$invalid_block]],
            ];
        }
    }

    public function invalid_values(): iterable
    {
        yield 'empty object' => [(object) []];
        yield 'not a value' => [(object) ['object' => 'inline', 'type' => 'mention']];
        yield 'no nodes' => [(object) ['object' => 'document', 'data' => (object) []]];
        yield 'invalid nodes' => [(object) ['object' => 'document', 'nodes' => false]];
        yield 'no data' => [(object) ['object' => 'document', 'nodes' => []]];
        yield 'invalid data' => [(object) ['object' => 'document', 'data' => null]];

        foreach ($this->invalid_documents() as $name => $invalid_document) {
            yield "valid value, invalid document({$name})" => [
                (object) ['object' => 'value', 'document' => $invalid_document],
            ];
        }
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
