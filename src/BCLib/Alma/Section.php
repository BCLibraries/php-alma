<?php

namespace BCLib\Alma;

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
 * @property ReadingList[] reading_lists
 * @property string[]      searchable_ids
 * @property string[]      reading_lists
 */
class Section
{
    protected $_terms = array();
    protected $_searchable_ids = array();
    protected $_reading_lists = array();

    /**
     * @var \SimpleXMLElement
     */
    protected $_xml;

    /**
     * @var ReadingList
     */
    protected $_list_prototpye;

    public function __construct(ReadingList $list_prototype)
    {
        $this->_list_prototpye = $list_prototype;
    }

    public function load(\SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
    }

    protected function _lazyLoadReadingLists()
    {
        if (count($this->_reading_lists) == 0) {
            foreach ($this->_xml->reading_lists->reading_list as $list_xml) {
                $list = clone $this->_list_prototpye;
                $list->load($list_xml);
                $this->_reading_lists[] = $list;
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
                return $this->_xml->course_information->$property;
            case 'processing_department':
                return $this->_xml->course_information->processingDepartment;
            case 'reading_lists':
                $this->_lazyLoadReadingLists();
                return $this->_reading_lists;
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
}