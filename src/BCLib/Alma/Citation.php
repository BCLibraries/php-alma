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
 * @property string type
 * @property string url
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
        switch ($property) {
            case 'identifier':
            case 'status':
            case 'type':
            case 'open_url':
                return (string) $this->_xml->$property;
            case 'author':
            case 'mms_id':
            case 'chapter':
            case 'pages':
            case 'note':
            case 'year':
            case 'call_number':
                return (string) $this->_xml->metadata->$property;
            case 'additional_person_name':
                return (string) $this->_xml->metadata->Additional_Person_Name;
            case 'place_of_publication':
                return (string) $this->_xml->metadata->Place_of_Publication;
            case 'url':
                return (string) $this->_xml->metadata->URL;
        }
    }

    public function jsonSerialize()
    {
        $citation = new \stdClass();
        $citation->identifier = $this->identifier;
        $citation->status = $this->status;
        $citation->type = $this->type;
        $citation->open_url = $this->open_url;
        $citation->author = $this->author;
        $citation->mms_id = $this->mms_id;
        $citation->chapter = $this->chapter;
        $citation->pages = $this->pages;
        $citation->note = $this->note;
        $citation->year = $this->year;
        $citation->call_number = $this->call_number;
        $citation->additional_person_name = $this->additional_person_name;
        $citation->place_of_publication = $this->place_of_publication;
        $citation->url = $this->url;
        return $citation;
    }
}