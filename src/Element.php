<?php

namespace Prezly\Slate;

interface Element extends Node
{
    /**
     * @return Node[]
     */
    public function getChildren(): array;
}
