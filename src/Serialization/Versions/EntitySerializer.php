<?php
namespace Prezly\Slate\Serialization\Versions;

use Prezly\Slate\Model\Value;
use stdClass;

/**
 * @internal Please do not use this class outside of this package.
 *           It's considered internal API and thus is not a subject for semantic versioning.
 *           The interface may change in future without major version bump.
 */
interface EntitySerializer
{
    /**
     * @param \Prezly\Slate\Model\Value $value
     * @return \stdClass
     */
    public function serializeValue(Value $value): stdClass;

    /**
     * @param \stdClass $value
     * @return \Prezly\Slate\Model\Value
     * @throws \InvalidArgumentException
     */
    public function unserializeValue(stdClass $value): Value;
}
