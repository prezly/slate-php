<?php

namespace Prezly\Slate;

use Prezly\Slate\Node\Block;
use Prezly\Slate\Node\Document;
use Prezly\Slate\Node\Inline;

use InvalidArgumentException;
use RuntimeException;
use stdClass;

class Unserializer
{
    public function fromJSON(string $json): Value
    {
        $data = json_decode($json, false);

        // TODO: Switch to JSON schema for validation
        if (! isset($data->object) || $data->object !== Object::VALUE || ! isset($data->document) || ! is_object($data->document) || $data->document->object !== Object::DOCUMENT) {
            throw new InvalidArgumentException("Root node must be a Slate document");
        }
        return $this->createValue($data);
    }

    private function createValue(stdClass $object): Value
    {
        $value = new Value();
        $value->setDocument($this->createDocument($object->document));

        return $value;
    }

    private function createDocument(stdClass $object): Document
    {
        $document = new Document();
        foreach ($object->nodes ?? [] as $child) {
            if ($child->object !== Object::BLOCK) {
                throw new InvalidArgumentException("Document node only supports Block child nodes");
            }
            $document->addNode($this->createBlock($child));
        }
        return $document;
    }

    private function createBlock(stdClass $object): Block
    {
        $block = new Block();
        $block->setType($object->type);
        foreach ($object->nodes ?? [] as $child) {
            $block->addNode($this->createObject($child));
        }
        return $block;
    }

    private function createInline(stdClass $object): Inline
    {
        $inline = new Inline();
        $inline->setType($object->type);
        foreach ($object->nodes ?? [] as $child) {
            $inline->addNode($this->createObject($child));
        }
        return $inline;
    }

    private function createText(stdClass $object): Text
    {
        $text = new Text();
        foreach ($object->leaves ?? [] as $child) {
            $text->addLeaf($this->createLeaf($child));
        }
        return $text;
    }

    private function createLeaf(stdClass $object): Leaf
    {
        $leaf = new Leaf();
        foreach ($object->marks ?? [] as $child) {
            $leaf->addMark($this->createMark($child));
        }
        return $leaf;
    }

    private function createMark(stdClass $object): Mark
    {
        $mark = new Mark();
        $mark->setType($object->type);
        return $mark;
    }

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