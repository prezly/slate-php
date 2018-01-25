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

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function setDocument(Document $document): void
    {
        $this->document = $document;
    }

    public function jsonSerialize()
    {
        return (object)[
            'object'   => Entity::VALUE,
            'document' => $this->document->jsonSerialize(),
        ];
    }
}
