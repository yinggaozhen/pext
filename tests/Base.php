<?php

namespace tests;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/Yaf.php';

/**
 * Class BasePTest
 * @package tests
 */
class Base extends TestCase
{
    /**
     * 为了避免yaf扩展对工程的影响
     * 需要关闭yaf扩展
     */
    public function setUp()
    {
        $this->assertFalse(extension_loaded("yaf"));
    }

    /**
     * @param $object
     * @param string $property
     * @return mixed
     * @throws \ReflectionException
     */
    public function proxyGetProperty($object, string $property)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($object);
    }
}
