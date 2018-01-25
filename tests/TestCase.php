<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Value;
use Prezly\Slate\Unserializer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadFixture(string $file_path): string
    {
        return file_get_contents($file_path);
    }

    protected function loadContent(string $filename): Value
    {
        $json = $this->loadFixture($filename);
        $unserializer = new Unserializer();
        return $unserializer->fromJSON($json);
    }
}
