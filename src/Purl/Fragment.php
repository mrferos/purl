<?php
namespace Purl;

use \ArrayAccess;

class Fragment implements ArrayAccess
{
    /**
     * @var Query
     */
    protected $_query;

    /**
     * @var Path
     */
    protected $_path;

    use QueryQuickAccessTrait;

    /**
     * @param $fragment
     */
    public function __construct($fragment)
    {
        $parts = parse_url($fragment);

        if (array_key_exists('path', $parts))
            $this->_path = new Path($parts['path']);

        if (array_key_exists('query', $parts))
            $this->_query = new Query($parts['query']);
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @return \Purl\Query
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $query = $this->getQuery();
        $path  = $this->getPath();
        $fragString = ltrim($path->toString(), '/');
        if (!empty($query))
            $fragString .= '?' . $query->toString();

        return $fragString;
    }

    public function __toString()
    {
        return $this->toString();
    }
}