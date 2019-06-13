<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Model\Value;

interface ValueUnserializer
{
    public function fromJson(string $json): Value;
}
