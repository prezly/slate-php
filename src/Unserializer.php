<?php

namespace Prezly\Slate;

use InvalidArgumentException;

class Unserializer
{
    public function fromJSON(string $json): Node
    {
        throw new InvalidArgumentException();
    }
}