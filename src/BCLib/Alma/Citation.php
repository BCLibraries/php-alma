<?php

namespace BCLib\Alma;

/**
 * Class Citation
 * @package BCLib\Alma
 *
 * @property string identifier
 * @property string status
 * @property string title
 * @property string call_number
 * @property string additional_person_name
 * @property string place_of_publication
 * @property string mms_id
 * @property string author
 * @property string chapter
 * @property string pages
 * @property string note
 * @property string year
 * @property string open_url
 */
abstract class Citation
{
    /**
     * @var \SimpleXMLElement
     */
    protected $_xml;

    public function load(\SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
    }

    public function __get($property)
    {
        $property = \strtolower($property);
        switch ($property) {
            case 'identifier':
            case 'status':
            case 'open_url':
                return (string) $this->_xml->$property;
            case 'author':
            case 'mms_id':
            case 'chapter':
            case 'pages':
            case 'note':
            case 'year':
            case 'additional_person_name':
            case 'place_of_publication':
            case 'call_number':
                return (string) $this->_xml->metadata->$property;
        }
    }
}