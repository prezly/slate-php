<?php
namespace Prezly\Slate\Serialization\Versions;

use InvalidArgumentException;
use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Support\ShapeValidator;
use RuntimeException;
use stdClass;

/**
 * Slate model serializer for Slate v0.46
 *
 * @see https://github.com/ianstormtaylor/slate/blob/master/packages/slate/Changelog.md#0460--may-1-2019
 *
 * @internal Please do not use this class outside of this package.
 *           It's considered internal API and thus is not a subject for semantic versioning.
 *           The interface may change in future without major version bump.
 */
class v0_46_VersionSerializer implements VersionSerializer
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
     * @param \Prezly\Slate\Model\Entity[] $entities
     * @return \stdClass[]
     */
    private function serializeEntities(array $entities): array
    {
        $serialized = [];
        foreach ($entities as $entity) {
            $serialized[] = $this->serializeEntity($entity);
        }

        return $serialized;
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
        if ($entity instanceof Mark) {
            return $this->serializeMark($entity);
        }
        throw new InvalidArgumentException('Unsupported entity type given: ' . get_class($entity));
    }

    /**
     * @param \stdClass[] $objects
     * @return \Prezly\Slate\Model\Entity[]
     */
    private function unserializeEntities(array $objects): array
    {
        $unserialized = [];
        foreach ($objects as $object) {
            $unserialized[] = $this->unserializeEntity($object);
        }
        return $unserialized;
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Entity
     */
    private function unserializeEntity(stdClass $object): Entity
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
            case Entity::MARK:
                return $this->unserializeMark($object);
            default:
                throw new RuntimeException("Unsupported object type: {$object->object}");
        }
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
            'nodes'  => $this->serializeEntities($document->getNodes()),
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

        return new Document($this->unserializeEntities($object->nodes), (array) $object->data);
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
            'nodes'  => $this->serializeEntities($block->getNodes()),
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

        return new Block($object->type, $this->unserializeEntities($object->nodes), (array) $object->data);
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
            'nodes'  => $this->serializeEntities($inline->getNodes()),
        ];
    }

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Inline
     * @throws \InvalidArgumentException
     */
    private function unserializeInline(stdClass $object): Inline
    {
        $object = ShapeValidator::validateSlateObject($object, Entity::INLINE, [
            'type'  => 'is_string',
            'data'  => 'is_object',
            'nodes' => 'is_array',
        ]);

        return new Inline($object->type, $this->unserializeEntities($object->nodes), (array) $object->data);
    }

    /**
     * @param \Prezly\Slate\Model\Text $text
     * @return \stdClass
     */
    private function serializeText(Text $text): stdClass
    {
        return (object) [
            'object' => Entity::TEXT,
            'text'   => $text->getText(),
            'marks'  => $this->serializeEntities($text->getMarks()),
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
            'text'  => 'is_string',
            'marks' => 'is_array',
        ]);

        return new Text($object->text, $this->unserializeEntities($object->marks));
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
