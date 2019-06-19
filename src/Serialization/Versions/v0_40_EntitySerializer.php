<?php
namespace Prezly\Slate\Serialization\Versions;

use InvalidArgumentException;
use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Support\ShapeValidator;
use RuntimeException;
use stdClass;

/**
 * Slate model serializer for Slate v0.40
 *
 * @see https://github.com/ianstormtaylor/slate/blob/master/packages/slate/Changelog.md#0400--august-22-2018
 *
 * @internal Please do not use this class outside of this package.
 *           It's considered internal API and thus is not a subject for semantic versioning.
 *           The interface may change in future without major version bump.
 */
class v0_40_EntitySerializer implements EntitySerializer
{
    /**
     * @param \Prezly\Slate\Model\Value $value
     * @return \stdClass
     */
    public function serializeValue(Value $value): stdClass
    {
        return (object) [
            'object'   => Entity::VALUE,
            'document' => $this->serializeDocument($value->getDocument()),
        ];
    }

    /**
     * @param \stdClass $value
     * @return \Prezly\Slate\Model\Value
     * @throws \InvalidArgumentException
     */
    public function unserializeValue(stdClass $value): Value
    {
        $value = ShapeValidator::validateSlateObject($value, Entity::VALUE, ['document' => 'is_object']);

        return new Value($this->unserializeDocument($value->document));
    }

    /**
     * @param \Prezly\Slate\Model\Entity $entity
     * @return \stdClass
     */
    private function serializeEntity(Entity $entity): stdClass
    {
        if ($entity instanceof Value) {
            return $this->serializeValue($entity);
        }
        if ($entity instanceof Document) {
            return $this->serializeDocument($entity);
        }
        if ($entity instanceof Block) {
            return $this->serializeBlock($entity);
        }
        if ($entity instanceof Inline) {
            return $this->serializeInline($entity);
        }
        if ($entity instanceof Text) {
            return $this->serializeText($entity);
        }
        if ($entity instanceof Leaf) {
            return $this->serializeLeaf($entity);
        }
        if ($entity instanceof Mark) {
            return $this->serializeMark($entity);
        }
        throw new InvalidArgumentException('Unsupported entity type given: ' . get_class($entity));
    }

    /**
     * @param \stdClass $entity
     * @return Object|Block|Inline|Text
     */
    private function unserializeEntity(stdClass $entity): Entity
    {
        $entity = ShapeValidator::validateSlateObject($entity); // generic slate object check

        switch ($entity->object) {
            case Entity::VALUE:
                return $this->unserializeValue($entity);

            case Entity::DOCUMENT:
                return $this->unserializeDocument($entity);

            case Entity::BLOCK:
                return $this->unserializeBlock($entity);

            case Entity::INLINE:
                return $this->unserializeInline($entity);

            case Entity::TEXT:
                return $this->unserializeText($entity);

            case Entity::LEAF:
                return $this->unserializeLeaf($entity);

            case Entity::MARK:
                return $this->unserializeMark($entity);
        }
        throw new RuntimeException("Unsupported object type: " . $entity->object);
    }

    /**
     * @param \Prezly\Slate\Model\Document $document
     * @return \stdClass
     */
    private function serializeDocument(Document $document): stdClass
    {
        return (object) [
            'object' => Entity::DOCUMENT,
            'data'   => (object) $document->getData(),
            'nodes'  => array_map(function (Block $block) {
                return $this->serializeBlock($block);
            }, $document->getNodes()),
        ];
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Document
     * @throws \InvalidArgumentException
     */
    private function unserializeDocument(stdClass $object): Document
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::DOCUMENT, [
            'data'  => 'is_object',
            'nodes' => 'is_array',
        ]);

        $nodes = [];
        foreach ($object->nodes as $node) {
            $nodes[] = $this->unserializeBlock($node);
        }

        return new Document($nodes, (array) $object->data);
    }

    /**
     * @param \Prezly\Slate\Model\Block $block
     * @return \stdClass
     */
    private function serializeBlock(Block $block): stdClass
    {
        return (object) [
            'object' => Entity::BLOCK,
            'type'   => $block->getType(),
            'data'   => (object) $block->getData(),
            'nodes'  => array_map(function (Entity $node) {
                return $this->serializeEntity($node);
            }, $block->getNodes())
        ];
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Block
     * @throws \InvalidArgumentException
     */
    private function unserializeBlock(stdClass $object): Block
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::BLOCK, [
            'type'  => 'is_string',
            'data'  => 'is_object',
            'nodes' => 'is_array',
        ]);

        $nodes = [];
        foreach ($object->nodes as $node) {
            $nodes[] = $this->unserializeEntity($node);
        }

        return new Block($object->type, $nodes, (array) $object->data);
    }

    /**
     * @param \Prezly\Slate\Model\Inline $inline
     * @return \stdClass
     */
    private function serializeInline(Inline $inline): stdClass
    {
        return (object) [
            'object' => Entity::INLINE,
            'type'   => $inline->getType(),
            'data'   => (object) $inline->getData(),
            'nodes'  => array_map(function (Entity $node) {
                return $this->serializeEntity($node);
            }, $inline->getNodes())
        ];
    }

    /**
     * @param \stdClass $inline
     * @return \Prezly\Slate\Model\Inline
     * @throws \InvalidArgumentException
     */
    private function unserializeInline(stdClass $inline): Inline
    {
        $inline = ShapeValidator::validateSlateObject($inline, Entity::INLINE, [
            'type'  => 'is_string',
            'data'  => 'is_object',
            'nodes' => 'is_array',
        ]);

        $nodes = [];
        foreach ($inline->nodes as $node) {
            $nodes[] = $this->unserializeEntity($node);
        }

        return new Inline($inline->type, $nodes, (array) $inline->data);
    }

    /**
     * @param \Prezly\Slate\Model\Text $text
     * @return \stdClass
     */
    private function serializeText(Text $text): stdClass
    {
        return (object) [
            'object' => Entity::TEXT,
            'leaves' => array_map(function (Leaf $leaf) {
                return $this->serializeLeaf($leaf);
            }, $text->getLeaves())
        ];
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Text
     * @throws \InvalidArgumentException
     */
    private function unserializeText(stdClass $object): Text
    {
        ShapeValidator::validateSlateObject($object, Entity::TEXT, [
            'leaves' => 'is_array',
        ]);

        $leaves = [];
        foreach ($object->leaves as $leaf) {
            $leaves[] = $this->unserializeLeaf($leaf);
        }

        return new Text($leaves);
    }

    /**
     * @param \Prezly\Slate\Model\Leaf $leaf
     * @return \stdClass
     */
    private function serializeLeaf(Leaf $leaf): stdClass
    {
        return (object) [
            'object' => Entity::LEAF,
            'text'   => $leaf->getText(),
            'marks'  => array_map(function (Mark $mark) {
                return $this->serializeMark($mark);
            }, $leaf->getMarks())
        ];
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Leaf
     * @throws \InvalidArgumentException
     */
    private function unserializeLeaf(stdClass $object): Leaf
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::LEAF, [
            'text'  => 'is_string',
            'marks' => 'is_array',
        ]);

        $marks = [];
        foreach ($object->marks as $mark) {
            $marks[] = $this->unserializeMark($mark);
        }

        return new Leaf($object->text, $marks);
    }

    /**
     * @param \Prezly\Slate\Model\Mark $mark
     * @return \stdClass
     */
    private function serializeMark(Mark $mark): stdClass
    {
        return (object) [
            'object' => Entity::MARK,
            'type'   => $mark->getType(),
            'data'   => (object) $mark->getData(),
        ];
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Mark
     * @throws \InvalidArgumentException
     */
    private function unserializeMark(stdClass $object): Mark
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::MARK, [
            'type' => 'is_string',
            'data' => 'is_object',
        ]);
        return new Mark($object->type, (array) $object->data);
    }
}
