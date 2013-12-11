<?php

namespace BCLib\Alma;

use Mockery\CountValidator\Exception;

/**
 * Class Section
 * @package BCLib\Alma
 *
 * @property string        identifier
 * @property string        code
 * @property string        section
 * @property string        name
 * @property string        faculty
 * @property string        status
 * @property string        start_date
 * @property string        end_date
 * @property string        hours
 * @property string        processing_department
 * @property string        participants
 * @property string        instructor_name
 * @property string        instructor_username
 * @property ReadingList[] complete_lists
 * @property ReadingList[] incomplete_lists
 * @property string[]      searchable_ids
 * @property string[]      terms
 */
class Section implements \JsonSerializable
{
    protected $_terms = array();
    protected $_searchable_ids = array();

    const SORT_TITLE = 'title';
    const SORT_AUTHOR = 'author';

    /**
     * @var ReadingList[]
     */
    protected $_complete_lists;

    /**
     * @var ReadingList[]
     */
    protected $_incomplete_lists;

    /**
     * @var \SimpleXMLElement
     */
    protected $_xml;

    /**
     * @var ReadingList
     */
    protected $_list_prototype;

    public function __construct(ReadingList $list_prototype)
    {
        $this->_list_prototype = $list_prototype;
    }

    public function load(\SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
    }

    protected function _lazyLoadReadingLists()
    {
        if (count($this->_complete_lists) + count($this->_incomplete_lists) == 0) {
            foreach ($this->_xml->reading_lists->reading_list as $list_xml) {
                $list = clone $this->_list_prototype;
                $list->load($list_xml);
                if ($list->status = "Complete") {
                    $this->_complete_lists[] = $list;
                } else {
                    $this->_incomplete_lists[] = $list;
                }
            }
        }
    }

    protected function _lazyLoadStrings(array &$target, \SimpleXMLElement $xml, $element_name)
    {
        if (count($target) == 0 && count($xml) > 0) {
            foreach ($xml->$element_name as $element_xml) {
                $target[] = (string) $element_xml;
            }
        }
    }

    public function sectionReadings($only_active = true, $sort = Section::SORT_TITLE)
    {
        if (($sort != Section::SORT_TITLE) && ($sort != Section::SORT_AUTHOR)) {
            throw new \Exception("$sort is not a valid sort type");
        }

        $readings = array();

        $lists = $this->complete_lists;

        if (!$only_active) {
            $lists = $lists + $this->incomplete_lists;
        }

        foreach ($lists as $list) {
            $readings = $readings + $list->citations;
        }

        usort(
            $readings,
            function ($a, $b) use ($sort) {

                if ($a->$sort > $b->$sort) {
                    return 1;
                }

                if ($a->$sort < $b->$sort) {
                    return -1;
                }

                return 0;
            }
        );

        return $readings;
    }

    public function __get($property)
    {
        switch ($property) {
            case 'identifier':
            case 'code':
            case 'section':
            case 'name':
            case 'faculty':
            case 'status':
            case 'start_date':
            case 'end_date':
            case 'hours':
            case 'participants':
                return (string) $this->_xml->course_information->$property;
            case 'instructor_name':
                return (string) $this->_xml->course_information->instructor->instructor;
            case 'instructor_username':
                return (string) $this->_xml->course_information->instructor->instructorUserName;
            case 'processing_department':
                return (string) $this->_xml->course_information->processingDepartment;
            case 'complete_lists':
                $this->_lazyLoadReadingLists();
                return $this->_complete_lists;
            case 'incomplete_lists':
                $this->_lazyLoadReadingLists();
                return $this->_incomplete_lists;
            case 'searchable_ids':
                $this->_lazyLoadStrings(
                    $this->_searchable_ids,
                    $this->_xml->course_information->searchableIds,
                    'searchableId'
                );
                return $this->_searchable_ids;
            case 'terms':
                $this->_lazyLoadStrings(
                    $this->_terms,
                    $this->_xml->course_information->terms,
                    'term'
                );
                return $this->_terms;
        }
    }

    public function __sleep()
    {
        // SimpleXMLElements can't be serialized. Convert to XML text.
        $this->_xml->addAttribute('xmlns:xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $this->_xml = $this->_xml->asXML();
        return array('_xml', '_list_prototype');
    }

    public function __wakeup()
    {
        $this->_xml = new \SimpleXMLElement($this->_xml);
    }

    public function jsonSerialize()
    {
        $section = new \stdClass();
        $section->identifier = $this->identifier;
        $section->complete_lists = $this->complete_lists;
        return $section;
    }
}