<?php

namespace Prezly\Slate\Model;

interface Object
{
    public const VALUE = "value";
    public const DOCUMENT = "document";
    public const BLOCK = "block";
    public const INLINE = "inline";
    public const TEXT = "text";
    public const LEAF = "leaf";
    public const MARK = "mark";
}