<?php

namespace Prezly\Slate\Model;

class Mark implements JsonConvertible
{
    use JsonStringConvertible;

    const OBJECT = 'mark';

    /** @var string */
    public $type;

    /** @var \stdClass */
    public $data;

    /**
     * @param string $type
     * @param \stdClass|null $data
     * @return void
     */
    public function __construct(string $type, \stdClass $data = null)
    {
        $this->type = $type;
        $this->data = $data ?? new \stdClass();
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'type'   => $this->type,
            'data'   => $this->data,
        ];
    }

    /**
     * @param \stdClass $object
     * @return self
     */
    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            $object->type,
            $object->data
        );
    }
}
