<?php
namespace Prezly\Slate\Serialization\Versions;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;
use stdClass;

/**
 * @internal Please do not use this class outside of this package.
 *           It's considered internal API and thus is not a subject for semantic versioning.
 *           The interface may change in future without major version bump.
 */
interface EntitySerializer
{
    /**
     * @param \Prezly\Slate\Model\Entity $entity
     * @return \stdClass
     */
    public function serializeEntity(Entity $entity): stdClass;

    /**
     * @param mixed $entity
     * @return Object|Block|Inline|Text
     * @throws \InvalidArgumentException
     */
    public function unserializeEntity(stdClass $entity): Entity;

    /**
     * @param \Prezly\Slate\Model\Value $value
     * @return \stdClass
     */
    public function serializeValue(Value $value): stdClass;

    /**
     * @param \stdClass $value
     * @return \Prezly\Slate\Model\Value
     * @throws \InvalidArgumentException
     */
    public function unserializeValue(stdClass $value): Value;

    /**
     * @param \Prezly\Slate\Model\Document $document
     * @return stdClass
     */
    public function serializeDocument(Document $document): stdClass;

    /**
     * @param stdClass $object
     * @return \Prezly\Slate\Model\Document
     */
    public function unserializeDocument(stdClass $object): Document;

    /**
     * @param \Prezly\Slate\Model\Block $block
     * @return \stdClass
     */
    public function serializeBlock(Block $block): stdClass;

    /**
     * @param \stdClass $object
     * @return \Prezly\Slate\Model\Block
     */
    public function unserializeBlock(stdClass $object): Block;

    /**
     * @param \Prezly\Slate\Model\Inline $inline
     * @return \stdClass
     */
    public function serializeInline(Inline $inline): stdClass;

    /**
     * @param stdClass $inline
     * @return \Prezly\Slate\Model\Inline
     */
    public function unserializeInline(stdClass $inline): Inline;

    /**
     * @param \Prezly\Slate\Model\Text $text
     * @return \stdClass
     */
    public function serializeText(Text $text): stdClass;

    /**
     * @param stdClass $object
     * @return \Prezly\Slate\Model\Text
     * @throws \InvalidArgumentException
     */
    public function unserializeText(stdClass $object): Text;

    /**
     * @param \Prezly\Slate\Model\Leaf $leaf
     * @return \stdClass
     */
    public function serializeLeaf(Leaf $leaf): stdClass;

    /**
     * @param stdClass $object
     * @return \Prezly\Slate\Model\Leaf
     * @throws \InvalidArgumentException
     */
    public function unserializeLeaf(stdClass $object): Leaf;

    /**
     * @param \Prezly\Slate\Model\Mark $mark
     * @return \stdClass
     */
    public function serializeMark(Mark $mark): stdClass;

    /**
     * @param stdClass $object
     * @return \Prezly\Slate\Model\Mark
     * @throws \InvalidArgumentException
     */
    public function unserializeMark(stdClass $object): Mark;
}
