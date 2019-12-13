<?php

namespace SGK\BarcodeBundle\Tests\Type;

use PHPUnit\Framework\TestCase;
use SGK\BarcodeBundle\Type\Type;

/**
 * Class TypeTest
 *
 * @package SGK\BarcodeBundle\Tests\Type
 */
class TypeTest extends TestCase
{
    /**
     * testConfigureOptions
     */
    public function testInvalidArgumentException()
    {
        $type = new Type();
        $this->expectException(\InvalidArgumentException::class);
        $type->getDimension('Unknown Type');
    }
}
