<?php

class PathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Purl\Purl
     */
    protected $_purl;

    public function setUp()
    {
        $this->_purl = \Purl\Purl::fromString(TEST_URL);
    }

    public function testHas()
    {
        $this->assertTrue($this->_purl->getPath()->has('path'));
    }

    public function testAdd()
    {
        $pathsToAdd = ['foo','foo2', 'foo3'];
        foreach ($pathsToAdd as $path)
            $this->_purl->getPath()->add($path);

        foreach ($pathsToAdd as $path)
            $this->assertTrue($this->_purl->getPath()->has($path));
    }

    public function testRemove()
    {
        $pathsToAdd = ['foo','foo2', 'foo3'];
        foreach ($pathsToAdd as $path)
            $this->_purl->getPath()->add($path);

        $this->_purl->getPath()->remove('foo2');

        $this->assertFalse($this->_purl->getPath()->has('foo2'));
    }

    public function testToStringAndEscape()
    {
        $pathsToAdd = ['this is a path', 'that', 'needs to be', 'escaped'];
        foreach ($pathsToAdd as $path)
            $this->_purl->getPath()->add($path);

        $this->assertEquals('/path/this+is+a+path/that/needs+to+be/escaped', $this->_purl->getPath()->toString());
    }
}