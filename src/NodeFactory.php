<?php

namespace Prezly\Slate;

use stdClass;

interface NodeFactory
{
    public function create(stdClass $object): ?Node;
}