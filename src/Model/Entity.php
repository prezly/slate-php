<?php

namespace Prezly\Slate\Model;

interface Entity extends \JsonSerializable
{
    const BLOCK = "block";
    const DOCUMENT = "document";
    const INLINE = "inline";
    const TEXT = "text";
    const VALUE = "value";
    const LEAF = "leaf";
    const MARK = "mark";
}
