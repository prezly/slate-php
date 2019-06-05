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

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $type
     * @return Mark new instance
     */
    public function withType(string $type): Mark
    {
        return new self($type, $this->data);
    }

    /**
     * @param array $data
     * @return Mark new instance
     */
    public function withData(array $data): Mark
    {
        return new self($this->type, $data);
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
