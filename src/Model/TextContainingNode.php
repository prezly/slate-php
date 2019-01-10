<?php

namespace Prezly\Slate\Model;

/**
 * @property-read string $text A concatenated string of all of the descendant Text nodes of this node.
 */
abstract class TextContainingNode implements JsonConvertible
{
    use JsonStringConvertible;

    /**
     * Concatenate all the descendant text nodes of this node.
     *
     * @return string
     */
    abstract protected function computeTextProperty(): string;

    /**
     * @param string $name
     *
     * @return string
     */
    public function __get(string $name): string
    {
        if($name === 'text'){
            return $this->computeTextProperty();
        }

        // http://de2.php.net/manual/en/language.oop5.overloading.php#object.get
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }
}
