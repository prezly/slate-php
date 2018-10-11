<?php

namespace Prezly\Slate;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;

use InvalidArgumentException;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use RuntimeException;
use stdClass;

class Unserializer
{
    public function fromJSON(string $json): Value
    {
        $data = json_decode($json, false);

        $this->validateIsSlateObject($data, Entity::VALUE, ['document' => 'is_object']);

        return $this->createValue($data);
    }

    /**
     * @param \stdClass|mixed $object
     * @param string|null $object_type
     * @param callable[] $shape [ $property_name => $checker, ... ]
     */
    private function validateIsSlateObject($object, string $object_type = null, array $shape = []): void
    {
        // Validate it's an stdClass
        if (!$object instanceof stdClass) {
            throw new InvalidArgumentException(sprintf(
                'Unexpected JSON value given: %s. An object is expected to construct %s.',
                gettype($object),
                ucfirst($object_type),
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
    }

    private function createValue(stdClass $object): Value
    {
        $this->validateIsSlateObject($object->document, Entity::DOCUMENT, [
            'data'  => 'is_object',
            'nodes' => 'is_array',
        ]);

        return new Value($this->createDocument($object->document));
    }

    private function createDocument(stdClass $object): Document
    {
        $nodes = [];
        foreach ($object->nodes as $node) {
            $this->validateIsSlateObject($node, Entity::BLOCK, [
                'data'  => 'is_object',
                'nodes' => 'is_array',
            ]);

            $nodes[] = $this->createBlock($node);
        }

        return new Document($nodes, (array) $object->data);
    }

    private function createBlock(stdClass $object): Block
    {
        $nodes = [];
        foreach ((array) $object->nodes as $node) {
            $this->validateIsSlateObject($node); // generic slate object check

            $nodes[] = $this->createObject($node);
        }

        return new Block($object->type, (array) $object->data, $nodes);
    }

    private function createInline(stdClass $object): Inline
    {
        $nodes = [];
        foreach ((array) $object->nodes as $node) {
            $this->validateIsSlateObject($object); // generic slate object check

            $nodes[] = $this->createObject($node);
        }

        return new Inline($object->type, (array) $object->data, $nodes);
    }

    private function createText(stdClass $object): Text
    {
        $leaves = [];
        foreach ($object->leaves as $leaf) {
            $this->validateIsSlateObject($leaf, Entity::LEAF, [
                'text'  => 'is_string',
                'marks' => 'is_array',
            ]);

            $leaves[] = $this->createLeaf($leaf);
        }

        return new Text($leaves);
    }

    private function createLeaf(stdClass $object): Leaf
    {
        $marks = [];
        foreach ($object->marks as $mark) {
            $this->validateIsSlateObject($mark, Entity::MARK, [
                'type' => 'is_string',
                'data' => 'is_object',
            ]);

            $marks[] = $this->createMark($mark);
        }
        return new Leaf($object->text, $marks);
    }

    private function createMark(stdClass $object): Mark
    {
        return new Mark($object->type);
    }

    /**
     * @param \stdClass $object
     * @return Object|Block|Inline|Text
     */
    private function createObject(stdClass $object): Entity
    {
        switch ($object->object) {
            case Entity::BLOCK:
                $this->validateIsSlateObject($object, Entity::BLOCK, [
                    'type'  => 'is_string',
                    'data'  => 'is_object',
                    'nodes' => 'is_array',
                ]);
                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return $this->createBlock($object);

            case Entity::INLINE:
                $this->validateIsSlateObject($object, Entity::INLINE, [
                    'type'  => 'is_string',
                    'data'  => 'is_object',
                    'nodes' => 'is_array',
                ]);
                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return $this->createInline($object);

            case Entity::TEXT:
                $this->validateIsSlateObject($object, Entity::TEXT, [
                    'leaves' => 'is_array',
                ]);
                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return $this->createText($object);
        }
        throw new RuntimeException("Unsupported object type: " . $object->object);
    }
}
