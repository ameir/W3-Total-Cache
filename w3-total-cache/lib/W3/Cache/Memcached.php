<?php

/**
 * PECL Memcached class
 */
if (!defined('W3TC')) {
    die();
}

require_once W3TC_LIB_W3_DIR . '/Cache/Base.php';

/**
 * Class W3_Cache_Memcached
 */
class W3_Cache_Memcached extends W3_Cache_Base {

    /**
     * Memcache object
     *
     * @var Memcache
     */
    var $_memcache = null;

    /**
     * PHP5 constructor
     *
     * @param array $config
     */
    function __construct($config) {
        $persistant = (isset($config['persistant']) && (boolean) $config['persistant']) ? 'w3tc' : null;

        @$this->_memcache = & new Memcached($persistant);

        if (!empty($config['servers'])) {

            if (isset($config['pgcache.memcached.compatibility']) && ($config['pgcache.memcached.compatibility'])) {
                $this->_memcache->setOption(Memcached::OPT_COMPRESSION, false);
            }

            foreach ((array) $config['servers'] as $server) {
                list($ip, $port) = explode(':', $server);
                $this->_memcache->addServer(trim($ip), (integer) trim($port));
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * PHP4 constructor
     *
     * @param array $config
     */
    function W3_Cache_Memcached($config) {
        $this->__construct($config);
    }

    /**
     * Adds data
     *
     * @param string $key
     * @param mixed $var
     * @param integer $expire
     * @return boolean
     */
    function add($key, &$var, $expire = 0) {
        return @$this->_memcache->add($key, $var, $expire);
    }

    /**
     * Sets data
     *
     * @param string $key
     * @param mixed $var
     * @param integer $expire
     * @return boolean
     */
    function set($key, &$var, $expire = 0) {
        // file_put_contents('/tmp/memcache.txt', $key.PHP_EOL.PHP_EOL.print_r($var,TRUE).PHP_EOL.PHP_EOL.PHP_EOL,FILE_APPEND);
        return @$this->_memcache->set($key, $var, $expire);
    }

    /**
     * Returns data
     *
     * @param string $key
     * @return mixed
     */
    function get($key) {
        return @$this->_memcache->get($key);
    }

    /**
     * Replaces data
     *
     * @param string $key
     * @param mixed $var
     * @param integer $expire
     * @return boolean
     */
    function replace($key, &$var, $expire = 0) {
        return @$this->_memcache->replace($key, $var, $expire);
    }

    /**
     * Deletes data
     *
     * @param string $key
     * @return boolean
     */
    function delete($key) {
        return @$this->_memcache->delete($key);
    }

    /**
     * Flushes all data
     *
     * @return boolean
     */
    function flush() {
        return @$this->_memcache->flush();
    }

}
