<?php
namespace Prezly\Slate\Tests\Serialization\Versions\v0_40;

use InvalidArgumentException;
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
     * @dataProvider invalid_marks
     * @param \stdClass $invalid_mark
     */
    public function it_should_throw_on_unserializing_invalid_marks(stdClass $invalid_mark): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeMark($invalid_mark);
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
     * @dataProvider invalid_leaves
     * @param \stdClass $invalid_leaf
     */
    public function it_should_throw_on_unserializing_invalid_leaves(stdClass $invalid_leaf): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeLeaf($invalid_leaf);
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
     * @dataProvider invalid_texts
     * @param \stdClass $invalid_text
     */
    public function it_should_throw_on_unserializing_invalid_texts(stdClass $invalid_text): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeText($invalid_text);
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
     * @dataProvider invalid_inlines
     * @param \stdClass $invalid_inline
     */
    public function it_should_throw_on_unserializing_invalid_inlines(stdClass $invalid_inline): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeInline($invalid_inline);
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
     * @dataProvider invalid_blocks
     * @param \stdClass $invalid_block
     */
    public function it_should_throw_on_unserializing_invalid_blocks(stdClass $invalid_block): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeBlock($invalid_block);
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
     * @dataProvider invalid_documents
     * @param \stdClass $invalid_document
     */
    public function it_should_throw_on_unserializing_invalid_documents(stdClass $invalid_document): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeDocument($invalid_document);
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

    /**
     * @test
     * @dataProvider invalid_entities
     * @param \stdClass $invalid_entity
     */
    public function it_should_throw_on_unserializing_invalid_entities(stdClass $invalid_entity): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer()->unserializeEntity($invalid_entity);
    }

    /**
     * @test
     */
    public function it_should_serialize_complete_slate_value(): void
    {
        $value = new Value(
            new Document(
                [
                    new Block('image', [], [
                        'src' => 'http://memory.loc.gov/pnp/cph/3d00000/3d02000/3d02000/3d02067r.jpg',
                    ]),
                    new Block('header', [
                        new Text([
                            new Leaf('Return to Sender'),
                        ]),
                    ]),
                    new Block('paragraph', [
                        new Inline('quote'),
                        new Text([
                            new Leaf('I gave a letter to the postman'),
                            new Leaf("He put it his sack", [new Mark('bold')]),
                            new Leaf('Bright in early next morning', [new Mark('underlined'), new Mark('bold')]),
                            new Leaf('He brought my letter back', [new Mark('underlined')]),
                        ]),
                    ]),
                ]
            )
        );

        $serialized = $this->serializer()->serializeValue($value);

        $this->assertEquals((object) [
            'object'   => 'value',
            'document' => (object) [
                'object' => 'document',
                'data'   => (object) [],
                'nodes'  => [
                    (object) [
                        'object' => 'block',
                        'type'   => 'image',
                        'data'   => (object) ['src' => 'http://memory.loc.gov/pnp/cph/3d00000/3d02000/3d02000/3d02067r.jpg'],
                        'nodes'  => [],
                    ],
                    (object) [
                        'object' => 'block',
                        'type'   => 'header',
                        'data'   => (object) [],
                        'nodes'  => [
                            (object) [
                                'object' => 'text',
                                'leaves' => [
                                    (object) [
                                        'object' => 'leaf',
                                        'text'   => 'Return to Sender',
                                        'marks'  => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    (object) [
                        'object' => 'block',
                        'type'   => 'paragraph',
                        'data'   => (object) [],
                        'nodes'  => [
                            (object) [
                                'object' => 'inline',
                                'type'   => 'quote',
                                'nodes'  => [],
                                'data'   => (object) [],
                            ],
                            (object) [
                                'object' => 'text',
                                'leaves' => [
                                    (object) [
                                        'object' => 'leaf',
                                        'text'   => 'I gave a letter to the postman',
                                        'marks'  => [],
                                    ],
                                    (object) [
                                        'object' => 'leaf',
                                        'text'   => 'He put it his sack',
                                        'marks'  => [
                                            (object) ['object' => 'mark', 'type' => 'bold', 'data' => (object) []],
                                        ],
                                    ],
                                    (object) [
                                        'object' => 'leaf',
                                        'text'   => 'Bright in early next morning',
                                        'marks'  => [
                                            (object) [
                                                'object' => 'mark',
                                                'type'   => 'underlined',
                                                'data'   => (object) [],
                                            ],
                                            (object) ['object' => 'mark', 'type' => 'bold', 'data' => (object) []],
                                        ],
                                    ],
                                    (object) [
                                        'object' => 'leaf',
                                        'text'   => 'He brought my letter back',
                                        'marks'  => [
                                            (object) [
                                                'object' => 'mark',
                                                'type'   => 'underlined',
                                                'data'   => (object) [],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $serialized);
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
