<?php

namespace BCLib\Alma;

/**
 * Class ReadingList
 * @package BCLib\Alma
 *
 * @property string     identifier
 * @property string     code
 * @property string     name
 * @property string     status
 * @property Citation[] citations
 */
class ReadingList
{
    protected $_xml;

    /**
     * @var Citation[]
     */
    protected $_citations = [];

    /**
     * @var CitationFactory
     */
    protected $_citation_factory;

    public function __construct(CitationFactory $citation_factory)
    {
        $this->_citation_factory = $citation_factory;
    }

    public function load(\SimpleXMLElement $list_xml)
    {
        $this->_xml = $list_xml;
    }

    protected function _lazyLoadCitations()
    {
        if (count($this->_citations) == 0 && isset($this->_xml->citations)) {
            foreach ($this->_xml->citations->citation as $citation_xml) {
                $citation = $this->_citation_factory->createCitation($citation_xml);
                $citation->load($citation_xml);
                $this->_citations[] = $citation;
            }
        }

        usort(
            $this->_citations,
            function ($a, $b) {

                if ($a->title > $b->title) {
                    return 1;
                }

                if ($a->title < $b->title) {
                    return -1;
                }

                return 0;
            }
        );
    }

    public function __get($name)
    {
        switch ($name) {
            case 'identifier':
            case 'code':
            case 'name':
            case 'status':
                return (string) $this->_xml->$name;
            case 'citations':
                $this->_lazyLoadCitations();
                return $this->_citations;
        }
    }

    public function jsonSerialize()
    {
        $list = new \stdClass();
        $list->identifier = $this->identifier;
        $list->code = $this->code;
        $list->name = $this->name;
        $list->status = $this->status;
        $list->citations = $this->citations;
        return $list;
    }
}