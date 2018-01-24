<?php

namespace Prezly\Slate;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;

use InvalidArgumentException;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Object;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use RuntimeException;
use stdClass;

class Unserializer
{
    public function fromJSON(string $json): Value
    {
        $data = json_decode($json, false);
        if (! isset($data->object) || $data->object !== Object::VALUE || ! isset($data->document) || ! is_object($data->document) || $data->document->object !== Object::DOCUMENT) {
            throw new InvalidArgumentException("Root node must be a Slate document");
        }
        return $this->createValue($data);
    }

    private function createValue(stdClass $object): Value
    {
        return new Value($this->createDocument($object->document));
    }

    private function createDocument(stdClass $object): Document
    {
        $nodes = [];
        foreach ($object->nodes as $node) {
            $nodes[] = $this->createBlock($node);
        }

        return new Document($nodes);
    }

    private function createBlock(stdClass $object): Block
    {
        $nodes = [];
        foreach ((array) $object->nodes as $node) {
            $nodes[] = $this->createObject($node);
        }

        return new Block($object->type, (array) $object->data, $nodes);
    }

    private function createInline(stdClass $object): Inline
    {
        $nodes = [];
        foreach ((array) $object->nodes as $node) {
            $nodes[] = $this->createObject($node);
        }

        return new Inline($object->type, (array) $object->data, $nodes);
    }

    private function createText(stdClass $object): Text
    {
        $leaves = [];
        foreach ($object->leaves as $leaf) {
            $leaves[] = $this->createLeaf($leaf);
        }

        return new Text($leaves);
    }

    private function createLeaf(stdClass $object): Leaf
    {
        $marks = [];
        foreach ($object->marks as $mark) {
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
    private function createObject(stdClass $object): Object
    {
        switch ($object->object) {
            case Object::BLOCK:
                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return $this->createBlock($object);
            case Object::INLINE:
                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return $this->createInline($object);
            case Object::TEXT:
                /** @noinspection PhpIncompatibleReturnTypeInspection */
                return $this->createText($object);
        }
        throw new RuntimeException("Unsupported object type: " . $object->object);
    }
}
