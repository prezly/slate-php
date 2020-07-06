<?php

namespace Prezly\Slate\Model;

use InvalidArgumentException;

abstract class Node
{
    /**
     * Get the concatenated text string of a node's content.
     *
     * Note that this will not include spaces or line breaks between block nodes.
     * This is not intended as a user-facing string, but as a string for performing
     * offset-related computations for a node.
     *
     * @param  Node  $root
     * @return string
     */
    public static function string(Node $root): string
    {
        if ($root instanceof Text) {
            return $root->getText();
        }
        if ($root instanceof Element) {
            $strings = array_map([Node::class, 'string'], $root->getChildren());
            return implode('', $strings);
        }
        throw new InvalidArgumentException(sprintf(
            'Unsupported Node subclass encountered (`%s`). Every node is required to implement either of Text or Element interfaces.',
            get_class($root),
        ));
    }
}
