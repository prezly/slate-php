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

class Serializer implements ValueSerializer
{
    public function toJson(Value $value, int $json_options = null): string
    {
        return json_encode($this->serializeEntity($value), $json_options ?? 0);
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

    private function serializeValue(Value $value): array
    {
        return [
            'object'   => Entity::VALUE,
            'document' => $this->serializeDocument($value->getDocument()),
        ];
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

    private function serializeInline(Inline $inline): array
    {
        return [
            'object' => Entity::INLINE,
            'type'   => $inline->getType(),
            'data'   => (object) $inline->getData(),
            'nodes'  => array_map(function (Entity $node) {
                return $this->serializeEntity($node->jsonSerialize());
            }, $inline->getNodes())
        ];
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

    private function serializeMark(Mark $mark): array
    {
        return [
            'object' => Entity::MARK,
            'type'   => $mark->getType(),
            'data'   => (object) $mark->getData(),
        ];
    }
}
