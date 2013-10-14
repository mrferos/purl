<?php

class PurlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Purl\Purl
     */
    protected $_purl;

    public function setUp()
    {
        $this->_purl = \Purl\Purl::fromString(TEST_URL);
    }

    public function testFromString()
    {
        $this->assertInstanceOf('\Purl\Purl', $this->_purl);
    }

    public function testUsernamePasswordParse()
    {

        $this->assertEquals('username', $this->_purl->getUsername());
        $this->assertEquals('password', $this->_purl->getPassword());
    }

    public function testHostPort()
    {
        $this->assertEquals('hostname', $this->_purl->getHost());
        $this->assertEquals('80', $this->_purl->getPort());
    }

    public function testFullMap()
    {
        $finalUrl = 'https://foo:newpassword@hostname:80/boom/stick?foo1=value1&foo2=value+2&f+o+o+3=v+%3F+l+u+%3E+3#another/anchor?with=queries';

        $this->_purl->query = ['foo1' => 'value1','foo2' => 'value 2', 'f o o 3' => 'v ? l u > 3'];
        $this->_purl->path = '/boom/stick';
        $this->_purl->fragment = 'another/anchor?with=queries';
        $this->_purl->username = 'foo';
        $this->_purl->password = 'newpassword';
        $this->_purl->scheme = 'https';
        $this->assertEquals($finalUrl, (string)$this->_purl);
    }
}