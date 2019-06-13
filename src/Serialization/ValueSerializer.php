<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Model\Value;

interface ValueSerializer
{
    public function toJson(Value $value, int $json_options = null): string;
}
