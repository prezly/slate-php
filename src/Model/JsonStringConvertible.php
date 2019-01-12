<?php

namespace Prezly\Slate\Model;

trait JsonStringConvertible
{
    /**
     * @param string $json
     * @return static
     */
    public static function fromJSON(string $json)
    {
        return static::jsonDeserialize(json_decode($json));
    }

    /**
     * @return static
     */
    public function toJSON()
    {
        return json_encode(static::jsonSerialize());
    }
    /**
     * @param \stdClass $object
     * @return static
     */
    abstract public static function jsonDeserialize(\stdClass $object);

    /**
     * @return \stdClass
     */
    abstract public function jsonSerialize(): \stdClass;
}
