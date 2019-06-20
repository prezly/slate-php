<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Model\Value;

interface ValueSerializer
{
    public function toJson(Value $value, ?string $version = null): string;

    public function fromJson(string $value, ?string $default_version = null): Value;
}
