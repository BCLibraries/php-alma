<?php

namespace BCLib\Alma;

use BCLib\Alma\AlmaCache;

class CourseServices
{
    /**
     * @var AlmaSoapClient
     */
    protected $_soap_client;
    protected $_section_prototype;

    /**
     * @var \BClib\Alma\AlmaCache
     */
    protected $_cache;

    protected $_cache_ttl;

    public function __construct(AlmaSoapClient $client, Section $section_prototype, AlmaCache $cache)
    {
        $this->_soap_client = $client;
        $this->_section_prototype = $section_prototype;

        $this->_cache = $cache;
        $this->_cache_ttl = 3600;
    }

    public function getCourse($identifier, $from = 0, $to = 10)
    {
        $query = "searchableId=$identifier";
        return $this->_sendQuery($query, $from, $to);
    }

    /**
     * @param      $course_number
     * @param null $section_number
     * @param int  $from
     * @param int  $to
     *
     * @return Section[]
     */
    public function getCourses($course_number, $section_number = null, $from = 0, $to = 10)
    {
        $key = $this->_courseCacheKey($course_number, $section_number);

        if (isset($section_number) && $this->_cache->contains($key)) {
            return array($this->_cache->read($key));
        }

        $query = "code=$course_number";
        if (isset($section_number)) {
            $query .= " and section=$section_number";
        }

        return $this->_sendQuery($query, $from, $to);
    }

    public function getCourseByTerm(
        $course_number,
        $section_number = null,
        \DateTime $date,
        $term,
        $refresh_cache = false
    ) {
        if ($refresh_cache) {
            $key = $this->_courseCacheKey($course_number, $section_number);
            $this->_cache->clear($key);
        }

        if (! is_array($term)) {
            $term = array($term);
        }

        $result = new \stdClass();
        $courses = $this->getCourses($course_number, $section_number);
        if (
            \count($courses) > 0 &&
            $date >= new \DateTime($courses[0]->start_date) &&
            $date <= new \DateTime($courses[0]->end_date) &&
            count(array_intersect($term, $courses[0]->terms)) > 0
        ) {
            $result = $courses[0];
        }
        return $result;
    }

    protected function _sendQuery($query, $from, $to)
    {
        $params = array('arg0' => $query, 'arg1' => $from, 'arg2' => $to);
        $base = $this->_soap_client->execute('searchCourseInformation', $params);
        if ($this->_soap_client->lastError() === false) {
            $sections = array();
            foreach ($base->results->course as $section_xml) {
                $section = clone $this->_section_prototype;
                $section->load($section_xml);
                $sections[] = $section;
                $cache_section = clone $section;
                $this->_cache->save($section->code . ":" . $section->section, $cache_section, $this->_cache_ttl);
            }
        } else {
            return false;
        }
        return $sections;
    }

    public function lastError()
    {
        return $this->_soap_client->lastError();
    }

    /**
     * Set cache time to live.
     *
     * @param $seconds int time-to-live in seconds
     */
    public function cacheTtl($seconds)
    {
        $this->_cache_ttl = $seconds;
    }

    protected function _courseCacheKey($course_number, $section_number)
    {
        return $this->_cache->key(get_class($this->_section_prototype), "$course_number:$section_number");
    }
}