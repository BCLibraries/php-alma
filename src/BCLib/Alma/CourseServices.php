<?php

namespace BCLib\Alma;

class CourseServices
{
    protected $_soap_client;
    protected $_section_prototype;

    public function __construct(AlmaSoapClient $client, Section $section_prototype)
    {
        $this->_soap_client = $client;
        $this->_section_prototype = $section_prototype;
    }

    public function getCourse($identifier)
    {
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
        $query = "code=$course_number";
        if (isset($section_number)) {
            $query .= " and section=$section_number";
        }

        $params = array('arg0' => $query, 'arg1' => $from, 'arg2' => $to);
        $base = $this->_soap_client->execute('searchCourseInformation', $params);
        if ($this->_soap_client->lastError() === false) {
            $sections = array();
            foreach ($base->results->course as $section_xml) {
                $section = clone $this->_section_prototype;
                $section->load($section_xml);
                $sections[] = $section;
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
}