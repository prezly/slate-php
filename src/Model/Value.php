<?php

namespace Prezly\Slate\Model;

class Value implements JsonConvertible
{
    use JsonStringConvertible;

    const OBJECT = 'value';

    /** @var Document */
    public $document;

    /**
     * @param Document $document
     * @return void
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @param \stdClass $value
     * @return self
     */
    public static function jsonDeserialize(\stdClass $value): self
    {
        return new self(
            Document::jsonDeserialize($value->document)
        );
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object'   => self::OBJECT,
            'document' => $this->document,
        ];
    }
}
