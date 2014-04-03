<?php

namespace BCLib\Alma;

use Doctrine\Common\Cache\Cache;

class AlmaCache
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    protected $_prefix;

    public function __construct(Cache $cache = null, $prefix = 'php-alma')
    {
        $this->_cache = $cache;
        $this->_prefix = $prefix;
    }

    public function save($id, $value, $lifetime)
    {
        $key = $this->key(get_class($value), $id);
        if ($this->_cache instanceof Cache) {
            $this->_cache->save($key, $value, $lifetime);
        }
    }

    public function read($key)
    {
        if (!$this->_cache instanceof Cache) {
            return null;
        }
        return $this->_cache->fetch($key);
    }

    public function clear($key) {
        $this->_cache->delete($key);
    }

    public function contains($key)
    {
        if (!$this->_cache instanceof Cache) {
            return false;
        }
        return $this->_cache->contains($key);
    }

    public function key($object_class, $id)
    {
        return $this->_prefix . ':' . $object_class . ':' . $id;
    }
}