<?php
namespace Prezly\Slate\Serialization;

use Prezly\Slate\Model\Entity;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Support\ShapeValidator;
use stdClass;

class Serializer implements ValueSerializer
{
    public const LATEST_SERIALIZATION_VERSION = '0.47';

    /** @var string */
    private $default_version;

    /** @var int */
    private $json_encode_options;

    /** @var \Prezly\Slate\Serialization\VersionSerializerFactory */
    private $factory;

    /**
     * @param string|null $default_version Default serialization version to use
     *                                     when serializing/unserializing with no version set.
     * @param int|null $json_encode_options JSON options to use for json_encode().
     * @param VersionSerializerFactory|null Factory used to get VersionSerializer for a given factory.
     */
    public function __construct(
        ?string $default_version = self::LATEST_SERIALIZATION_VERSION,
        int $json_encode_options = null,
        ?VersionSerializerFactory $factory = null
    ) {
        $this->default_version = $default_version ?? self::LATEST_SERIALIZATION_VERSION;
        $this->json_encode_options = $json_encode_options;
        $this->factory = $factory ?? new DefaultVersionSerializerFactory();
    }

    /**
     * Serialize value to JSON
     *
     * Optionally you can provide desired serialization version.
     *
     * If no version argument provided, default serialization version
     * will be used (which is set to LATEST by default).
     *
     * @param \Prezly\Slate\Model\Value $value
     * @param string|null $version
     * @return string
     * @throws \Prezly\Slate\Serialization\Exceptions\UnsupportedVersionException
     */
    public function toJson(Value $value, ?string $version = null): string
    {
        return json_encode(
            $this->serializeValue($value, $version),
            $this->json_encode_options
        );
    }

    /**
     * Unserialize value from JSON
     *
     * Optional you can provide serialization version to use
     * in case if value JSON does not have "version" property.
     *
     * If no version argument is given, default serialization
     * version will be implied (which is set to LATEST by default).
     *
     * @param string $value
     * @param string|null $default_version
     * @return \Prezly\Slate\Model\Value
     * @throws \Prezly\Slate\Serialization\Exceptions\UnsupportedVersionException
     */
    public function fromJson(string $value, ?string $default_version = null): Value
    {
        return $this->unserializeValue(json_decode($value, false), $default_version);
    }

    private function serializeValue(Value $value, ?string $version): stdClass
    {
        $version = $version ?? $this->default_version;
        $object = $this->factory->getSerializer($version)->serializeValue($value);
        $object->version = $version;

        return $object;
    }

    private function unserializeValue($value, ?string $default_version = null): Value
    {
        $object = ShapeValidator::validateSlateObject($value, Entity::VALUE);
        $version = $object->version ?? $default_version ?? $this->default_version;

        return $this->factory->getSerializer($version)->unserializeValue($object);
    }
}
