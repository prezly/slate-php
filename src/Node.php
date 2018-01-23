<?php

namespace Prezly\Slate;

/**
 * @todo Add getText() method
 * @see https://docs.slatejs.org/slate-core/node
 */
interface Node extends Object
{
    /**
     * @return Node[]
     */
    public function getNodes(): array;
}