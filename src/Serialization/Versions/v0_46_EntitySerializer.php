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
 * @see https://github.com/ianstormtaylor/slate/blob/master/packages/slate/Changelog.md#0460--may-1-2019
 *
 * @internal Please do not use this class outside of this package.
 *           It's considered internal API and thus is not a subject for semantic versioning.
 *           The interface may change in future without major version bump.
 */
class v0_46_EntitySerializer implements EntitySerializer
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
            if ($entity instanceof Value) {
                $serialized[] = $this->serializeValue($entity);
                continue;
            }
            if ($entity instanceof Document) {
                $serialized[] = $this->serializeDocument($entity);
                continue;
            }
            if ($entity instanceof Block) {
                $serialized[] = $this->serializeBlock($entity);
                continue;
            }
            if ($entity instanceof Inline) {
                $serialized[] = $this->serializeInline($entity);
                continue;
            }
            if ($entity instanceof Text) {
                foreach ($this->serializeText($entity) as $serialized_text) {
                    $serialized[] = $serialized_text;
                }
                continue;
            }
            if ($entity instanceof Mark) {
                $serialized[] = $this->serializeMark($entity);
                continue;
            }
            throw new InvalidArgumentException('Unsupported entity type given: ' . get_class($entity));
        }

        return $serialized;
    }

    /**
     * @param \stdClass[] $entities
     * @return \Prezly\Slate\Model\Entity[]
     */
    private function unserializeEntities(array $entities): array
    {
        $unserialized = [];
        foreach ($entities as $entity) {
            $entity = ShapeValidator::validateSlateObject($entity); // generic slate object check

            switch ($entity->object) {
                case Entity::VALUE:
                    $unserialized[] = $this->unserializeValue($entity);
                    break;
                case Entity::DOCUMENT:
                    $unserialized[] = $this->unserializeDocument($entity);
                    break;
                case Entity::BLOCK:
                    $unserialized[] = $this->unserializeBlock($entity);
                    break;
                case Entity::INLINE:
                    $unserialized[] = $this->unserializeInline($entity);
                    break;
                case Entity::TEXT:
                    $unserialized[] = $this->unserializeText($entity);
                    break;
                case Entity::MARK:
                    $unserialized[] = $this->unserializeMark($entity);
                    break;
                default:
                    throw new RuntimeException("Unsupported object type: " . $entity->object);
            }
        }
        return $this->collapseTextNodes($unserialized);
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
     * @return \stdClass[]
     */
    private function serializeText(Text $text): array
    {
        if (count($text->getLeaves()) === 0) {
            // Return empty text node if there are no leaves to map to text nodes
            return [
                (object) [
                    'object' => Entity::TEXT,
                    'text'   => '',
                    'marks'  => [],
                ],
            ];
        }

        $texts = [];
        foreach ($text->getLeaves() as $leaf) {
            // Map v0.40 leaves to v0.46 text nodes (forward compatibility)
            $texts[] = (object) [
                'object' => Entity::TEXT,
                'text'   => $leaf->getText(),
                'marks'  => array_map(function (Mark $mark) {
                    return $this->serializeMark($mark);
                }, $leaf->getMarks()),
            ];
        }

        return $texts;
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

        $marks = [];
        foreach ($object->marks as $mark) {
            $marks[] = $this->unserializeMark($mark);
        }

        if ($object->text === '' && count($marks) === 0) {
            // Return empty text node if there's no actual content to map to leaves (no text, no marks)
            return new Text();
        }

        // Auto-convert v0.46 text node to v0.40 leaf-containing text node (forward compatibility)
        return new Text([
            new Leaf($object->text, $marks),
        ]);
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

    /**
     * @param \Prezly\Slate\Model\Entity[] $nodes
     * @return \Prezly\Slate\Model\Entity[]
     */
    private function collapseTextNodes(array $nodes): array
    {
        if (count($nodes) <= 1) {
            // nothing to do
            return $nodes;
        }

        $collapsed = [];
        do {
            $prev = array_pop($collapsed);
            $curr = array_shift($nodes);

            if ($prev === null) {
                $collapsed[] = $curr;
                continue;
            }

            if ($prev instanceof Text && $curr instanceof Text) {
                // Combine two Text nodes and push them to the list
                $collapsed[] = $prev->withLeaves(array_merge($prev->getLeaves(), $curr->getLeaves()));
                continue;
            }

            $collapsed[] = $prev; // Push prev back to the list
            $collapsed[] = $curr; // Push curr to the list

        } while (count($nodes) > 0);

        return $collapsed;
    }
}
