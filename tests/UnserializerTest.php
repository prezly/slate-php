<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;

use InvalidArgumentException;

class UnserializerTest extends TestCase
{
    /**
     * The top level node of the Slate model is the Value. Any JSON that doesn't
     * have a top-level document node is invalid.
     *
     * @see https://docs.slatejs.org/slate-core/value
     * @see https://docs.slatejs.org/slate-core/document
     */
    public function test_should_expect_top_level_document()
    {
        $this->markTestSkipped('TODO add better validation errors');
        $this->expectException(InvalidArgumentException::class);
        Value::fromJSON('{}');
    }

    /**
     * Since the Slate model always has a top-level node (the Value), the result of unserialization
     * should always be a single Value instance
     */
    public function test_should_return_document_node()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/empty_document.json');
        $this->assertEmpty($document->nodes);
    }

    public function test_should_set_document_data()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_data.json');

        $this->assertEquals(
            (object) ['foo' => 'bar'],
            $document->data
        );
    }

    public function test_should_only_accept_block_nodes_for_document()
    {
        $this->markTestSkipped('TODO add better validation errors');
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/document_with_inline_children.json');
        $this->expectException(InvalidArgumentException::class);

        Value::fromJSON($fixture);
    }

    public function test_should_add_children_to_document()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_flat_children.json');
        $nodes = $document->nodes;

        $this->assertCount(3, $nodes);
        foreach ($nodes as $node) {
            $this->assertInstanceOf(Block::class, $node);
        }

        $this->assertEquals('paragraph', $nodes[0]->type);
        $this->assertEquals('blockquote', $nodes[1]->type);
        $this->assertEquals('list', $nodes[2]->type);
    }

    public function test_should_nest_children()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_nested_children.json');
        $children = $document->nodes;

        // Second-level children
        $this->assertCount(2, $children[0]->nodes);
        $this->assertCount(0, $children[1]->nodes);
        $this->assertCount(2, $children[2]->nodes);
    }

    public function test_should_load_document_with_leaves()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_text.json');

        $this->assertCount(1, $document->nodes);
        $block = $document->nodes[0];

        /** @var Block $block */
        $this->assertInstanceOf(Block::class, $block);
        $this->assertEquals('paragraph', $block->type);
        $this->assertCount(1, $block->nodes);

        /** @var Text $text */
        $text = $block->nodes[0];
        $this->assertInstanceOf(Text::class, $text);
        $this->assertCount(6, $text->leaves);

        $expected_texts = [
            'I\'d like to introduce ',
            'you',
            ' to a ',
            'very important ',
            'person',
            '!',
        ];

        foreach ($text->leaves as $i => $leaf) {
            $this->assertInstanceOf(Leaf::class, $leaf);
            $this->assertEquals($expected_texts[$i], $leaf->text);
        }
    }

    public function test_should_set_node_data()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/nodes_with_data.json');
        $block = $document->nodes[0];
        $this->assertEquals(
            (object) ['foo' => 'bar'],
            $block->data
        );

        /** @var Inline $inline */
        $inline = $block->nodes[0];
        $this->assertEquals(
            (object) ['name' => 'John Doe', 'id' => 1234],
            $inline->data
        );
    }

    public function test_should_set_mark_data()
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . "/fixtures/mark_with_data.json");
        $leaf = $document->nodes[0]->nodes[0]->leaves[0];
        $this->assertEquals(
            (object) ["href" => "/foo"],
            $leaf->marks[0]->data
        );
    }

    /**
     * @dataProvider invalid_documents_fixtures
     *
     * @param string $file
     * @param string $expected_error
     */
    public function test_should_fail_deserializing_invalid_structure(string $file, string $expected_error)
    {
        $this->markTestSkipped('TODO add better validation errors');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expected_error);
        $this->loadDocumentFromFixture($file);
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
