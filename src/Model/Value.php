<?php

namespace Prezly\Slate\Model;

class Value implements Entity
{
    /** @var Document */
    private $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @deprecated Deprecated in favor of immutable API. Use withDocument() instead.
     * @see withDocument()
     * @param Document $document
     */
    public function setDocument(Document $document): void
    {
        $this->document = $document;
    }

    /**
     * @param Document $document
     * @return Value New instance
     */
    public function withDocument(Document $document): Value
    {
        return new self($document);
    }

    public function jsonSerialize()
    {
        return (object) [
            'object'   => Entity::VALUE,
            'document' => $this->document->jsonSerialize(),
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
