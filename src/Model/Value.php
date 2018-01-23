<?php

namespace Prezly\Slate\Model;

class Value implements Object
{
    /** @var Document */
    private $document;

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function setDocument(Document $document)
    {
        $this->document = $document;
    }
}