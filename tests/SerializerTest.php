<?php
namespace Prezly\Slate\Tests;

use InvalidArgumentException;
use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Serialization\Serializer;

class SerializerTest extends TestCase
{
    /** @var \Prezly\Slate\Serialization\Serializer */
    private $serializer;

    protected function setUp()
    {
        parent::setUp();
        $this->serializer = new Serializer();
    }

    /**
     * @test
     * @dataProvider valid_documents_jsons
     *
     * @param string $file_path
     */
    public function it_should_serialize_to_json(string $file_path)
    {
        $json = $this->loadFixture($file_path);
        $value = $this->loadValueFromFixture($file_path);

        $json = implode("\n",
            array_map(function (string $line): string {
                return preg_replace('/^(\s+)/', '$1$1', $line);
            }, explode("\n", $json))
        );

        $this->assertEquals(trim($json), $value->toJson(JSON_PRETTY_PRINT));
    }

    /**
     * The top level node of the Slate model is the Value. Any JSON that doesn't
     * have a top-level document node is invalid
     *
     * @see https://docs.slatejs.org/slate-core/value
     * @see https://docs.slatejs.org/slate-core/document
     * @test
     */
    public function it_should_expect_top_level_document()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->serializer->fromJson("{}");
    }

    /**
     * Since the Slate model always has a top-level node (the Value), the result of unserialization
     * should always be a single Value instance
     *
     * @test
     */
    public function it_should_return_document_node()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/empty_document.json");
        $this->assertEmpty($document->getNodes());
    }

    /**
     * @test
     */
    public function it_should_set_document_data()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/document_with_data.json");
        $this->assertEquals(["foo" => "bar"], $document->getData());
    }

    public function it_should_only_accept_block_nodes_for_document()
    {
        $fixture = $this->loadFixture(__DIR__ . "/fixtures/document_with_inline_children.json");
        $this->expectException(InvalidArgumentException::class);
        $this->serializer->fromJson($fixture);
    }

    /**
     * @test
     */
    public function it_should_add_children_to_document()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/document_with_flat_children.json");
        $nodes = $document->getNodes();

        $this->assertEquals(3, count($nodes));
        foreach ($nodes as $node) {
            $this->assertInstanceOf(Block::class, $node);
        }

        $this->assertEquals("paragraph", $nodes[0]->getType());
        $this->assertEquals("blockquote", $nodes[1]->getType());
        $this->assertEquals("list", $nodes[2]->getType());
    }

    /**
     * @test
     */
    public function it_should_nest_children()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/document_with_nested_children.json");
        $children = $document->getNodes();

        // Second-level children
        $this->assertEquals(2, count($children[0]->getNodes()));
        $this->assertEquals(0, count($children[1]->getNodes()));
        $this->assertEquals(2, count($children[2]->getNodes()));
    }

    /**
     * @test
     */
    public function it_should_load_document_with_leaves()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/document_with_text.json");

        $this->assertCount(1, $document->getNodes());
        $block = $document->getNodes()[0];

        /** @var Block $block */
        $this->assertInstanceOf(Block::class, $block);
        $this->assertEquals('paragraph', $block->getType());
        $this->assertCount(3, $block->getNodes());

        /** @var Text $text */
        $text = $block->getNodes()[0];
        $this->assertInstanceOf(Text::class, $text);
        $this->assertCount(3, $text->getLeaves());

        $expected_texts = [
            "I'd like to introduce ",
            'you',
            ' to a ',
        ];

        foreach ($text->getLeaves() as $i => $leaf) {
            $this->assertInstanceOf(Leaf::class, $leaf);
            $this->assertEquals($expected_texts[$i], $leaf->getText());
        }
    }
    /**
     * @test
     */
    public function it_should_set_node_data()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/nodes_with_data.json");
        $block = $document->getNodes()[0];
        $this->assertEquals(["foo" => "bar"], $block->getData());

        /** @var Inline $inline */
        $inline = $block->getNodes()[0];
        $this->assertEquals(["name" => "John Doe", "id" => 1234], $inline->getData());
    }

    /**
     * @test
     */
    public function it_should_set_mark_data()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/mark_with_data.json");
        $leaf = $document->getNodes()[0]->getNodes()[0]->getLeaves()[0];
        $this->assertEquals(["href" => "/foo"], $leaf->getMarks()[0]->getData());
    }

    /**
     * @test
     * @dataProvider invalid_documents_fixtures
     *
     * @param string $file
     * @param string $expected_error
     */
    public function it_should_fail_deserializing_invalid_structure(string $file, string $expected_error)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expected_error);
        $this->loadDocumentFromFixture($file);
    }

    public function valid_documents_jsons(): array
    {
        return [
            'document_with_text.json' => [__DIR__ . '/fixtures/document_with_text.json'],
        ];
    }

    /**
     * @see it_should_fail_deserializing_invalid_structure
     */
    public function invalid_documents_fixtures()
    {
        return [
            [__DIR__ . '/fixtures/invalid_document_01.json', 'Unexpected JSON value given: integer. An object is expected to construct Value.'],
            [__DIR__ . '/fixtures/invalid_document_02.json', 'Unexpected JSON value given: string. An object is expected to construct Value.'],
            [__DIR__ . '/fixtures/invalid_document_03.json', 'Invalid JSON structure given to construct Value. It should have "object" property.'],
            [__DIR__ . '/fixtures/invalid_document_04.json', 'Invalid JSON structure given to construct Value. It should have "object" property set to "value".'],
            [__DIR__ . '/fixtures/invalid_document_05.json', 'Unexpected JSON structure given for Value. A Value should have "document" property.'],
            [__DIR__ . '/fixtures/invalid_document_06.json', 'Unexpected JSON structure given for Value. The "document" property should be object.'],
            [__DIR__ . '/fixtures/invalid_document_07.json', 'Invalid JSON structure given to construct Document. It should have "object" property set to "document".'],
            [__DIR__ . '/fixtures/invalid_document_08_invalid_leaf_object.json', 'Invalid JSON structure given to construct Leaf. It should have "object" property set to "leaf".'],
        ];
    }
}
