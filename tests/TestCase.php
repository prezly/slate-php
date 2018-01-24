<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Value;
use Prezly\Slate\Unserializer;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadFixture(string $fixture): string
    {
        return file_get_contents(__DIR__ . "/fixtures/$fixture");
    }

    protected function loadContent(string $filename): Value
    {
        $json = $this->loadFixture($filename);
        $unserializer = new Unserializer();
        return $unserializer->fromJSON($json);
    }
}
