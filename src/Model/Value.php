<?php

namespace Prezly\Slate\Model;

use Prezly\Slate\Serialization\Serializer;

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
     * @param Document $document
     * @return Value New instance
     */
    public function withDocument(Document $document): Value
    {
        return new self($document);
    }

    public function toJson(int $options = 0): string
    {
        $serializer = new Serializer($options);
        return $serializer->toJson($this);
    }
}
