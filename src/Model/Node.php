<?php

namespace Prezly\Slate\Model;

interface Node extends Entity
{
    /**
     * @return Node[]
     */
    public function getNodes(): array;

    /**
     * @return string
     */
    public function getText(): string;
}
