<?php

namespace Prezly\Slate\Model;

class Document extends Element
{
    /**
     * @return string
     */
    public function getText(): string
    {
        return Node::string($this);
    }
}
