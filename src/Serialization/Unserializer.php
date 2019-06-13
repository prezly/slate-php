<?php

namespace Prezly\Slate\Serialization;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;

use RuntimeException;

class Unserializer implements ValueUnserializer
{
    public function fromJson(string $json): Value
    {
        $data = json_decode($json, false);

        return $this->unserializeValue($data);
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

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Value
     */
    private function unserializeValue($object): Value
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::VALUE, ['document' => 'is_object']);

        return new Value($this->unserializeDocument($object->document));
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
