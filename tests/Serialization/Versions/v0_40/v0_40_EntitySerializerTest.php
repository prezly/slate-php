<?php
namespace Prezly\Slate\Tests\Serialization\Versions\v0_40;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Entity;
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
     * @dataProvider marks
     * @param \Prezly\Slate\Model\Mark $mark
     * @param \stdClass $serialized
     */
    public function it_should_serialize_marks(Mark $mark, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeMark($mark)
        );
    }

    /**
     * @test
     * @dataProvider marks
     * @param \Prezly\Slate\Model\Mark $mark
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_marks(Mark $mark, stdClass $serialized): void
    {
        $this->assertEquals(
            $mark,
            $this->serializer()->unserializeMark($serialized)
        );
    }

    /**
     * @test
     * @dataProvider leaves
     * @param \Prezly\Slate\Model\Leaf $leaf
     * @param \stdClass $serialized
     */
    public function it_should_serialize_leaves(Leaf $leaf, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeLeaf($leaf)
        );
    }

    /**
     * @test
     * @dataProvider leaves
     * @param \Prezly\Slate\Model\Leaf $leaf
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_leaves(Leaf $leaf, stdClass $serialized): void
    {
        $this->assertEquals(
            $leaf,
            $this->serializer()->unserializeLeaf($serialized)
        );
    }

    /**
     * @test
     * @dataProvider texts
     * @param \Prezly\Slate\Model\Text $text
     * @param \stdClass $serialized
     */
    public function it_should_serialize_texts(Text $text, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeText($text)
        );
    }

    /**
     * @test
     * @dataProvider texts
     * @param \Prezly\Slate\Model\Text $text
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_texts(Text $text, stdClass $serialized): void
    {
        $this->assertEquals(
            $text,
            $this->serializer()->unserializeText($serialized)
        );
    }

    /**
     * @test
     * @dataProvider inlines
     * @param \Prezly\Slate\Model\Inline $inline
     * @param \stdClass $serialized
     */
    public function it_should_serialize_inlines(Inline $inline, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeInline($inline)
        );
    }

    /**
     * @test
     * @dataProvider inlines
     * @param \Prezly\Slate\Model\Inline $inline
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_inlines(Inline $inline, stdClass $serialized): void
    {
        $this->assertEquals(
            $inline,
            $this->serializer()->unserializeInline($serialized)
        );
    }

    /**
     * @test
     * @dataProvider blocks
     * @param \Prezly\Slate\Model\Block $block
     * @param \stdClass $serialized
     */
    public function it_should_serialize_blocks(Block $block, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeBlock($block)
        );
    }

    /**
     * @test
     * @dataProvider blocks
     * @param \Prezly\Slate\Model\Block $block
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_blocks(Block $block, stdClass $serialized): void
    {
        $this->assertEquals(
            $block,
            $this->serializer()->unserializeBlock($serialized)
        );
    }

    /**
     * @test
     * @dataProvider documents
     * @param \Prezly\Slate\Model\Document $document
     * @param \stdClass $serialized
     */
    public function it_should_serialize_documents(Document $document, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeDocument($document)
        );
    }

    /**
     * @test
     * @dataProvider documents
     * @param \Prezly\Slate\Model\Document $document
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_documents(Document $document, stdClass $serialized): void
    {
        $this->assertEquals(
            $document,
            $this->serializer()->unserializeDocument($serialized)
        );
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
     * @dataProvider entities
     * @param \Prezly\Slate\Model\Entity $entity
     * @param \stdClass $serialized
     */
    public function it_should_serialize_entities(Entity $entity, stdClass $serialized): void
    {
        $this->assertEquals(
            $serialized,
            $this->serializer()->serializeEntity($entity)
        );
    }

    /**
     * @test
     * @dataProvider entities
     * @param \Prezly\Slate\Model\Entity $entity
     * @param \stdClass $serialized
     */
    public function it_should_unserialize_entities(Entity $entity, stdClass $serialized): void
    {
        $this->assertEquals(
            $entity,
            $this->serializer()->unserializeEntity($serialized)
        );
    }

    public function marks(): iterable
    {
        yield 'Mark(bold)' => $bold_mark = [
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
    }

    public function entities(): iterable
    {
        yield from $this->marks();
        yield from $this->leaves();
        yield from $this->texts();
        yield from $this->inlines();
        yield from $this->blocks();
        yield from $this->documents();
        yield from $this->values();
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
