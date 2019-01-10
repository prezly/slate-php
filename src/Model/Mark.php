<?php

namespace Prezly\Slate\Model;

class Mark implements Entity
{
    /** @var string */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::MARK,
            'type'   => $this->type,
            'data'   => (object) [],
        ];
    }
}
