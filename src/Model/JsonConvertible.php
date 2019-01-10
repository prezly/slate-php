<?php

namespace Prezly\Slate\Model;

interface JsonConvertible extends \JsonSerializable
{
    /**
     * @param \stdClass $object
     * @return static
     */
    public static function jsonDeserialize(\stdClass $object);

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass;
}
