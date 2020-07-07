<?php

namespace Prezly\Slate;

interface Text extends Node
{
    /**
     * @return string
     */
    public function getText(): string;
}
