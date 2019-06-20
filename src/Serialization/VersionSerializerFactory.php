<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Serialization\Versions\VersionSerializer;

interface VersionSerializerFactory
{
    /**
     * @param string $version
     * @return \Prezly\Slate\Serialization\Versions\VersionSerializer
     * @throws \Prezly\Slate\Serialization\Exceptions\UnsupportedVersionException
     */
    public function getSerializer(string $version): VersionSerializer;
}
