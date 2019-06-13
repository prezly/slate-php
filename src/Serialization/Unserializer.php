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

use InvalidArgumentException;
use RuntimeException;
use stdClass;

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
        $object = $this->validateSlateObject($object); // generic slate object check

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
     * @param \stdClass|mixed $object
     * @param string|null $object_type
     * @param callable[] $shape [ string $property_name => string $checker, ... ]
     * @return \stdClass
     */
    private function validateSlateObject($object, string $object_type = null, array $shape = []): stdClass
    {
        // Validate it's an stdClass
        if (!$object instanceof stdClass) {
            throw new InvalidArgumentException(sprintf(
                'Unexpected JSON value given: %s. An object is expected to construct %s.',
                gettype($object),
                ucfirst($object_type) ?: 'a Slate structure object'
            ));
        }

        // Validate "object" property presence
        if (!property_exists($object, 'object')) {
            throw new InvalidArgumentException(sprintf(
                'Invalid JSON structure given to construct %s. It should have "object" property.',
                ucfirst($object_type)
            ));
        }

        // Validate "object" property value
        if ($object_type !== null && $object_type !== $object->object) {
            throw new InvalidArgumentException(sprintf(
                'Invalid JSON structure given to construct %s. It should have "object" property set to "%s".',
                ucfirst($object_type),
                $object_type
            ));
        }

        // Validate Shape
        foreach ($shape as $property => $checker) {
            if (!property_exists($object, $property)) {
                throw new InvalidArgumentException(sprintf(
                    'Unexpected JSON structure given for %s. A %s should have "%s" property.',
                    ucfirst($object_type),
                    ucfirst($object_type),
                    $property
                ));
            }
            if (!$checker($object->$property)) {
                throw new InvalidArgumentException(sprintf(
                    'Unexpected JSON structure given for %s. The "%s" property should be %s.',
                    ucfirst($object_type),
                    $property,
                    substr($checker, 0, 3) === 'is_' ? substr($checker, 3) : $checker
                ));
            }
        }

        return $object;
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Value
     */
    private function unserializeValue($object): Value
    {
        $object = $this->validateSlateObject($object, Entity::VALUE, ['document' => 'is_object']);

        return new Value($this->unserializeDocument($object->document));
    }

    /**
     * @param mixed $object
     * @return \Prezly\Slate\Model\Document
     */
    private function unserializeDocument($object): Document
    {
        $object = $this->validateSlateObject($object, Entity::DOCUMENT, [
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
        $object = $this->validateSlateObject($object, Entity::BLOCK, [
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
        $object = $this->validateSlateObject($object, Entity::INLINE, [
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
        $this->validateSlateObject($object, Entity::TEXT, [
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
        $object = $this->validateSlateObject($object, Entity::LEAF, [
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
        $object = $this->validateSlateObject($object, Entity::MARK, [
            'type' => 'is_string',
            'data' => 'is_object',
        ]);
        return new Mark($object->type, (array) $object->data);
    }
}
