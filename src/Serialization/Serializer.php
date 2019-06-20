<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Exceptions\UnsupprotedVersionException;
use Prezly\Slate\Serialization\Support\ShapeValidator;
use Prezly\Slate\Serialization\Versions\EntitySerializer;
use Prezly\Slate\Serialization\Versions\v0_40_EntitySerializer;
use Prezly\Slate\Serialization\Versions\v0_46_EntitySerializer;
use stdClass;

class Serializer implements ValueSerializer
{
    public const LATEST_SERIALIZATION_VERSION = '0.47';

    private const SERIALIZATION_VERSIONS = [
        '0.40' => v0_40_EntitySerializer::class,
        '0.41' => v0_40_EntitySerializer::class,
        '0.42' => v0_40_EntitySerializer::class,
        '0.43' => v0_40_EntitySerializer::class,
        '0.44' => v0_40_EntitySerializer::class,
        '0.45' => v0_40_EntitySerializer::class,
        // 0.46 - leaves data combined into text nodes
        '0.46' => v0_46_EntitySerializer::class,
        '0.47' => v0_46_EntitySerializer::class,
    ];

    /** @var string */
    private $default_version;

    /** @var int */
    private $json_encode_options;

    public function __construct(?string $default_version = self::LATEST_SERIALIZATION_VERSION, int $json_encode_options = null)
    {
        $this->default_version = $default_version ?? self::LATEST_SERIALIZATION_VERSION;
        $this->json_encode_options = $json_encode_options;
    }

    public function toJson(Value $value, ?string $version = null): string
    {
        return json_encode(
            $this->serializeValue($value, $version),
            $this->json_encode_options
        );
    }

    public function fromJson(string $value, ?string $default_version = null): Value
    {
        return $this->unserializeValue(json_decode($value, false));
    }

    private function serializeValue(Value $value, ?string $version): stdClass
    {
        $version = $version ?? $this->default_version;
        $object = $this->getSerializer($version)->serializeValue($value);
        $object->version = $version;

        return $object;
    }

    private function unserializeValue($value, ?string $default_version = null): Value
    {
        $object = ShapeValidator::validateSlateObject($value, Entity::VALUE);
        $version = $object->version ?? $default_version ?? $this->default_version;

        return $this->getSerializer($version)->unserializeValue($object);
    }

    /**
     * @param string $version
     * @return \Prezly\Slate\Serialization\Versions\EntitySerializer
     */
    private function getSerializer(string $version): EntitySerializer
    {
        $generic_version = implode('.', array_slice(explode('.', $version), 0, 2));

        if (! isset(self::SERIALIZATION_VERSIONS[$generic_version])) {
            throw new UnsupprotedVersionException($version);
        }

        $serializer_class = self::SERIALIZATION_VERSIONS[$generic_version];
        /** @var \Prezly\Slate\Serialization\Versions\EntitySerializer $serializer */
        $serializer = new $serializer_class();

        return $serializer;
    }
}
