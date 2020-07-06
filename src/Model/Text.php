<?php

namespace Prezly\Slate\Model;

abstract class Text extends Node
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function withText(string $text): string
    {
        $that = clone $this;
        $that->text = $text;

        return $that;
    }
}
