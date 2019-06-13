<?php
namespace Prezly\Slate\Serialization;

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

class Serializer implements ValueSerializer
{
    public function toJson(Value $value, int $json_options = null): string
    {
        return json_encode($this->serializeEntity($value), $json_options ?? 0);
    }

    public function fromJson(string $value): Value
    {
        return $this->unserializeValue(json_decode($value, false));
    }

    private function serializeEntity(Entity $entity): array
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
     * @param mixed $object
     * @return Object|Block|Inline|Text
     */
    private function unserializeEntity($object): Entity
    {
        $object = ShapeValidator::validateSlateObject($object); // generic slate object check

        switch ($object->object) {
            case Entity::VALUE:
                return $this->unserializeValue($object);

            case Entity::DOCUMENT:
                return $this->unserializeDocument($object);

            case Entity::BLOCK:
                return $this->unserializeBlock($object);

            case Entity::INLINE:
                return $this->unserializeInline($object);

            case Entity::TEXT:
                return $this->unserializeText($object);

            case Entity::LEAF:
                return $this->unserializeLeaf($object);

            case Entity::MARK:
                return $this->unserializeMark($object);
        }
        throw new RuntimeException("Unsupported object type: " . $object->object);
    }

    private function serializeValue(Value $value): array
    {
        return [
            'object'   => Entity::VALUE,
            'document' => $this->serializeDocument($value->getDocument()),
        ];
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Value
     */
    private function unserializeValue($object): Value
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::VALUE, ['document' => 'is_object']);

        return new Value($this->unserializeDocument($object->document));
    }

    private function serializeDocument(Document $document): array
    {
        return [
            'object' => Entity::DOCUMENT,
            'data'   => (object) $document->getData(),
            'nodes'  => array_map(function (Block $block) {
                return $this->serializeBlock($block);
            }, $document->getNodes()),
        ];
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Document
     */
    private function unserializeDocument($object): Document
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

    private function serializeBlock(Block $block): array
    {
        return [
            'object' => Entity::BLOCK,
            'type'   => $block->getType(),
            'data'   => (object) $block->getData(),
            'nodes'  => array_map(function (Entity $node) {
                return $this->serializeEntity($node);
            }, $block->getNodes())
        ];
    }

    private function unserializeBlock($object): Block
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

    private function serializeInline(Inline $inline): array
    {
        return [
            'object' => Entity::INLINE,
            'type'   => $inline->getType(),
            'data'   => (object) $inline->getData(),
            'nodes'  => array_map(function (Entity $node) {
                return $this->serializeEntity($node);
            }, $inline->getNodes())
        ];
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Inline
     */
    private function unserializeInline($object): Inline
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::INLINE, [
            'type'  => 'is_string',
            'data'  => 'is_object',
            'nodes' => 'is_array',
        ]);

        $nodes = [];
        foreach ($object->nodes as $node) {
            $nodes[] = $this->unserializeEntity($node);
        }

        return new Inline($object->type, $nodes, (array) $object->data);
    }

    private function serializeText(Text $text): array
    {
        return [
            'object' => Entity::TEXT,
            'leaves' => array_map(function (Leaf $leaf) {
                return $this->serializeLeaf($leaf);
            }, $text->getLeaves())
        ];
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Text
     */
    private function unserializeText($object): Text
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

    private function serializeLeaf(Leaf $leaf): array
    {
        return [
            'object' => Entity::LEAF,
            'text'   => $leaf->getText(),
            'marks'  => array_map(function (Mark $mark) {
                return $this->serializeMark($mark);
            }, $leaf->getMarks())
        ];
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Leaf
     */
    private function unserializeLeaf($object): Leaf
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

    private function serializeMark(Mark $mark): array
    {
        return [
            'object' => Entity::MARK,
            'type'   => $mark->getType(),
            'data'   => (object) $mark->getData(),
        ];
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Mark
     */
    private function unserializeMark($object): Mark
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::MARK, [
            'type' => 'is_string',
            'data' => 'is_object',
        ]);
        return new Mark($object->type, (array) $object->data);
    }
}
