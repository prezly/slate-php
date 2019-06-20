<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Serialization\Exceptions\UnsupprotedVersionException;
use Prezly\Slate\Serialization\Versions\v0_40_VersionSerializer;
use Prezly\Slate\Serialization\Versions\v0_46_VersionSerializer;
use Prezly\Slate\Serialization\Versions\VersionSerializer;

class DefaultVersionSerializerFactory implements VersionSerializerFactory
{
    private const SERIALIZATION_VERSIONS = [
        '0.40' => v0_40_VersionSerializer::class,
        '0.41' => v0_40_VersionSerializer::class,
        '0.42' => v0_40_VersionSerializer::class,
        '0.43' => v0_40_VersionSerializer::class,
        '0.44' => v0_40_VersionSerializer::class,
        '0.45' => v0_40_VersionSerializer::class,
        // 0.46 - leaves data combined into text nodes
        '0.46' => v0_46_VersionSerializer::class,
        '0.47' => v0_46_VersionSerializer::class,
    ];

    /** @var array */
    private $serialization_versions;

    public function __construct(array $serialization_versions = null)
    {
        $this->serialization_versions = $serialization_versions ?? self::SERIALIZATION_VERSIONS;
    }

    public function getSerializer(string $version): VersionSerializer
    {
        $generic_version = implode('.', array_slice(explode('.', $version), 0, 2));

        if (! isset($this->serialization_versions[$generic_version])) {
            throw new UnsupprotedVersionException($version);
        }

        $serializer_class = $this->serialization_versions[$generic_version];
        /** @var \Prezly\Slate\Serialization\Versions\VersionSerializer $serializer */
        $serializer = new $serializer_class();

        return $serializer;
    }
}
