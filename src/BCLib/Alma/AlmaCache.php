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
        if ($this->_cache instanceof Cache) {
            $key = $this->_sectionCacheKey($section->code, $section->section);
            $this->_cache->save($key, $section, $lifetime);
        }
    }

    public function getSection($code, $section)
    {
        if (!$this->_cache instanceof Cache) {
            return null;
        }
        $key = $this->_sectionCacheKey($code, $section);
        return $this->_cache->fetch($key);
    }

    public function containsSection($code, $section)
    {
        if (!$this->_cache instanceof Cache) {
            return false;
        }
        $key = $this->_sectionCacheKey($code, $section);
        return $this->_cache->contains($key);
    }

    protected function _sectionCacheKey($code, $section)
    {
        return $this->_prefix . ":section:" . $code . ":" . $section;
    }

} 