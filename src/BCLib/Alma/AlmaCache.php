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

    public function saveSection(Section $section, $lifetime = false)
    {
        $key = $this->_sectionCacheKey($section->code, $section->section);
        $this->_save($key, $section, $lifetime);
    }

    public function saveUser(User $user, $lifetime = false)
    {
        $key = $this->_userCacheKey($user->username);
        $this->_save($key, $user, $lifetime);
    }

    public function _save($key, $value, $lifetime)
    {
        if ($this->_cache instanceof Cache) {
            $this->_cache->save($key, $value, $lifetime);
        }
    }

    public function getSection($code, $section)
    {
        $key = $this->_sectionCacheKey($code, $section);
        return $this->_read($key);
    }

    public function getUser($id)
    {
        $key = $this->_sectionCacheKey($id);
        return $this->_read($key);
    }

    protected function _read($key)
    {
        if (!$this->_cache instanceof Cache) {
            return null;
        }
        return $this->_cache->fetch($key);
    }

    public function containsSection($code, $section)
    {
        $key = $this->_sectionCacheKey($code, $section);
        return $this->_contains($key);
    }

    public function containsUser($id)
    {
        $key = $this->_userCacheKey($id);
        return $this->_contains($key);
    }

    protected function _contains($key)
    {
        if (!$this->_cache instanceof Cache) {
            return false;
        }
        return $this->_cache->contains($key);
    }

    protected function _sectionCacheKey($code, $section)
    {
        return $this->_prefix . ":section:" . $code . ":" . $section;
    }

    protected function _userCacheKey($id)
    {
        return $this->_prefix . ":user:" . strtolower($id);
    }
} 