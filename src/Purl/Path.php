<?php
namespace Purl;

class Path
{
    /**
     * @var array
     */
    protected $_segments;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->_segments = explode('/', ltrim($path, '/'));
    }

    /**
     * Add a segment to the end of the path
     *
     * @param $segment
     */
    public function add($segment)
    {
        if (is_array($segment)) {
            $this->_segments += $segment;
        } else {
            $this->_segments[] = $segment;
        }
    }

    /**
     * @param $segment
     */
    public function remove($segment)
    {
        if (in_array($segment, $this->_segments)) {
            unset ($this->_segments[array_search($segment, $this->_segments)]);
        }
    }

    /**
     * @param $segment
     * @return bool
     */
    public function has($segment)
    {
        return array_search($segment, $this->_segments) !== false;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return '/' . implode('/', array_map('urlencode', $this->_segments));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}