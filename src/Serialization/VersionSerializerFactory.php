<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Serialization\Versions\VersionSerializer;

interface VersionSerializerFactory
{
    public function getSerializer(string $version): VersionSerializer;
}
