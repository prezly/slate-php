<?php

namespace Prezly\Slate\Model;

class Mark implements Entity
{
    /** @var string */
    private $type;

    /** @var array */
    private $data;

    public function __construct(string $type, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return (object) [
            'object' => Entity::MARK,
            'type'   => $this->type,
            'data'   => (object) $this->data,
        ];
    }
}
