<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Unserializer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadFixture(string $file_path): string
    {
        return file_get_contents($file_path);
    }

    protected function loadContentFromFixture(string $file_path): Value
    {
        $json = $this->loadFixture($file_path);
        $unserializer = new Unserializer();
        return $unserializer->fromJSON($json);
    }

    protected function loadDocumentFromFixture(string $file_path): Document
    {
        return $this->loadContentFromFixture($file_path)->getDocument();
    }
}
