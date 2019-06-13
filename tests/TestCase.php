<?php

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Value;
use Prezly\Slate\Serialization\Serializer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadFixture(string $file_path): string
    {
        return file_get_contents($file_path);
    }

    protected function loadContentFromFixture(string $file_path): Value
    {
        $json = $this->loadFixture($file_path);
        $serializer = new Serializer();
        return $serializer->fromJson($json);
    }

    protected function loadDocumentFromFixture(string $file_path): Document
    {
        return $this->loadContentFromFixture($file_path)->getDocument();
    }
}
