<?php

namespace Prezly\Slate;

class Mark implements Object
{
    /** @var string */
    private $type;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }
}