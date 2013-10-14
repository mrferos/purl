<?php
namespace Purl;

use \RuntimeException;
use \ArrayAccess;

class Purl implements ArrayAccess
{
    /**
     * A map of schemes and their
     * default ports
     *
     * @var array
     */
    protected $_mappedPorts = array(
        'ftp'   => 21,
        'ssh'   => 22,
        'http'  => 80,
        'https' => 443
    );

    /**
     * @var Path
     */
    protected $_path;

    /**
     * @var Query
     */
    protected $_query;

    /**
     * @var Fragment
     */
    protected $_fragment;

    protected $_username,
        $_password,
        $_scheme,
        $_host,
        $_port;


    use QueryQuickAccessTrait;

    public function __construct($url, $strict = false)
    {
        $parts = parse_url($url);
        if (false == $parts)
            throw new RuntimeException('Could not parse url, may be malformed');

        if (array_key_exists('user', $parts))
            $this->_username = $parts['user'];

        if (array_key_exists('pass', $parts))
            $this->_password = $parts['pass'];

        if (array_key_exists('scheme', $parts))
            $this->_scheme = $parts['scheme'];

        if (array_key_exists('host', $parts))
            $this->_host = $parts['host'];

        if (array_key_exists('path', $parts))
            $this->_path = new Path($parts['path']);

        if (array_key_exists('port', $parts))
            $this->_port = $parts['port'];

        if (array_key_exists('query', $parts))
            $this->_query = new Query($parts['query']);

        if (array_key_exists('fragment', $parts))
            $this->_fragment = new Fragment($parts['fragment']);
    }

    public function __get($var)
    {
        return property_exists($this, '_' . $var) ? call_user_func([$this, 'get' . ucfirst($var)]) : null;
    }

    public function __set($var, $data)
    {
        switch ($var) {
            case 'fragment':
                $this->_fragment = new Fragment($data);
                break;
            case 'query':
                $this->_query = new Query($data);
                break;
            case 'path':
                $this->_path = new Path($data);
            default:
                if (!isset($this->{"_" . $var}))
                    throw new RuntimeException('Invalid property "' . $var .'"');
                call_user_func([$this, 'set' . ucfirst($var)], $data);
        }
    }

    /**
     * @param \Purl\Fragment $fragment
     */
    public function setFragment($fragment)
    {
        $this->_fragment = $fragment;
    }

    /**
     * @return Fragment
     */
    public function getFragment()
    {
        return $this->_fragment;
    }

    /**
     * @param $host
     */
    public function setHost($host)
    {
        $this->_host = $host;
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param \Purl\Path $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @return \Purl\Path
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param \Purl\Query $query
     */
    public function setQuery($query)
    {
        $this->_query = $query;
    }

    /**
     * @return \Purl\Query
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @param $scheme
     */
    public function setScheme($scheme)
    {
        $this->_scheme = $scheme;
    }

    /**
     * @return string|null
     */
    public function getScheme()
    {
        return $this->_scheme;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param $port
     */
    public function setPort($port)
    {
        $this->_port = $port;
    }

    /**
     * @return mixed|null
     */
    public function getPort($tryToGuess = false)
    {
        if (empty($this->_port) && $tryToGuess) {
            $scheme = $this->getScheme();
            return array_key_exists($scheme, $this->_mappedPorts) ?
                    $this->_mappedPorts[$scheme] : null;
        }

        return $this->_port;
    }

    public function toString()
    {
        $password = $this->getPassword();
        $userPassString = $this->getUsername();
        if (!empty($password))
            $userPassString .= ':' . $password;

        if (!empty($userPassString))
            $userPassString .= '@';

        $url = $this->getScheme() . '://' .
               $userPassString .
               $this->getHost();

        $port = $this->getPort();
        if (!empty($port))
            $url .= ':' . $port;

        $path = $this->getPath();
        if (!empty($path))
            $url .= (string)$path;

        $query = $this->getQuery();
        if (!empty($query))
            $url .= '?' . (string)$query;

        $fragment = $this->getFragment();
        if (!empty($fragment))
            $url .= '#' . (string)$fragment;

        return $url;

    }

    public function __toString()
    {
        return $this->toString();
    }

    public static function fromString($url)
    {
        return new Purl($url);
    }
}