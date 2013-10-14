<?php
namespace Purl;

use \ArrayAccess;
use \RuntimeException;

class Query implements ArrayAccess
{
    protected $_args;

    /**
     * @param string|array $queryString
     * @throws RuntimeException
     */
    public function __construct($queryString)
    {
        if (is_array($queryString)) {
            $this->_args = $queryString;
        } else {
            parse_str($queryString, $queryParts);
            if (false == $queryParts)
                throw new RuntimeException('Could not parse query string, possibly malformed');

            $this->_args = $queryParts;
        }
    }

    /**
     * Add/update query arguments
     *
     * @param string|array $part
     * @param null $val
     */
    public function add($part, $val = null)
    {
        if (is_array($part)) {
            $this->_args += $part;
        } else {
            $this->_args[$part] = $val;
        }

    }

    /**
     * Retrieve an argument from the key
     *
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
         return $this->has($key) ? $this->_args[$key] : null;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->_args;
    }

    /**
     * Check if an argument exist in the query
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->_args);
    }

    /**
     * Remove an argument from the query
     *
     * @param $key
     */
    public function remove($key)
    {
        if ($this->has($key))
            unset($this->_args[$key]);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return http_build_query($this->_args);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

}