<?php

namespace Prezly\Slate\Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadFixture(string $file_path): string
    {
        return file_get_contents($file_path);
    }
}
