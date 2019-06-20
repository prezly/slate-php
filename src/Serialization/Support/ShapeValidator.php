<?php
namespace Prezly\Slate\Serialization\Support;

use InvalidArgumentException;
use stdClass;

/**
 * @internal Please do not use this class outside of this package.
 *           It's considered internal API and thus is not a subject for semantic versioning.
 *           The interface may change in future without major version bump.
 */
class ShapeValidator
{
    /**
     * @param \stdClass|mixed $object
     * @param string|null $object_type
     * @param callable[] $shape [ string $property_name => string $check_function, ... ]
     * @return \stdClass
     * @throws \InvalidArgumentException
     */
    public static function validateSlateObject($object, string $object_type = null, array $shape = []): stdClass
    {
        // Validate it's an stdClass
        if (! $object instanceof stdClass) {
            throw new InvalidArgumentException(sprintf(
                'Unexpected JSON value given: %s. An object is expected to construct %s.',
                gettype($object),
                ucfirst($object_type) ?: 'a Slate structure object'
            ));
        }

        // Validate "object" property presence
        if (! property_exists($object, 'object')) {
            throw new InvalidArgumentException(sprintf(
                'Invalid JSON structure given to construct %s. It should have "object" property.',
                ucfirst($object_type)
            ));
        }

        // Validate "object" property value
        if ($object_type !== null && $object_type !== $object->object) {
            throw new InvalidArgumentException(sprintf(
                'Invalid JSON structure given to construct %s. It should have "object" property set to "%s".',
                ucfirst($object_type),
                $object_type
            ));
        }

        // Validate Shape
        foreach ($shape as $property => $checker) {
            if (! property_exists($object, $property)) {
                throw new InvalidArgumentException(sprintf(
                    'Unexpected JSON structure given for %s. A %s should have "%s" property.',
                    ucfirst($object_type),
                    ucfirst($object_type),
                    $property
                ));
            }
            if (! $checker($object->$property)) {
                throw new InvalidArgumentException(sprintf(
                    'Unexpected JSON structure given for %s. The "%s" property should be %s.',
                    ucfirst($object_type),
                    $property,
                    substr($checker, 0, 3) === 'is_' ? substr($checker, 3) : $checker
                ));
            }
        }

        return $object;
    }
}
