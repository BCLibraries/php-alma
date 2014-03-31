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
        $this->save($key, $section, $lifetime);
    }

    public function saveUser(User $user, $lifetime = false)
    {
        $key = $this->_userCacheKey($user->username);
        $this->save($key, $user, $lifetime);
    }

    public function saveBibRecord(SoapBibRecord $record, $lifetime = false)
    {
        $key = $this->_bibRecordCacheKey($record->mms);
        $this->save($key, $record, $lifetime);
    }

    public function save($key, $value, $lifetime)
    {
        if ($this->_cache instanceof Cache) {
            $this->_cache->save($key, $value, $lifetime);
        }
    }

    public function getSection($code, $section)
    {
        $key = $this->_sectionCacheKey($code, $section);
        return $this->read($key);
    }

    public function getUser($id)
    {
        $key = $this->_userCacheKey($id);
        return $this->read($key);
    }

   public function getBibRecord($mms)
    {
        $key = $this->_bibRecordCacheKey($mms);
        return $this->read($key);
    }

    public function read($key)
    {
        if (!$this->_cache instanceof Cache) {
            return null;
        }
        return $this->_cache->fetch($key);
    }

    public function containsSection($code, $section)
    {
        $key = $this->_sectionCacheKey($code, $section);
        return $this->contains($key);
    }

    public function containsUser($id)
    {
        $key = $this->_userCacheKey($id);
        return $this->contains($key);
    }

    public function containsBibRecord($mms)
    {
        $key = $this->_bibRecordCacheKey($mms);
        return $this->contains($key);
    }

    public function clearSection($course_code, $section_number)
    {
        $key = $this->_sectionCacheKey($course_code, $section_number);
        $this->_cache->delete($key);
    }

    public function clearUser($id)
    {
        $key = $this->_userCacheKey($id);
        $this->_cache->delete($key);
    }

    public function contains($key)
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

    protected function _bibRecordCacheKey($mms)
    {
        return $this->_prefix . ":bib_record:" . $mms;
    }

    public function key($type, $id)
    {
        return $this->_prefix . ':' . $type . ':' . $id;
    }
}