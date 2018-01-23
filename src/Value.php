<?php

namespace Prezly\Slate;

use Prezly\Slate\Node\Document;

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