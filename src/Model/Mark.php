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

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @deprecated Deprecated in favor of immutable API. Use withType() instead.
     * @see withType()
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @deprecated Deprecated in favor of immutable API. Use withData() instead.
     * @see withData()
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string $type
     * @return Mark New instance
     */
    public function withType(string $type): Mark
    {
        return new self($type, $this->data);
    }

    /**
     * @param array $data
     * @return Mark New instance
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
