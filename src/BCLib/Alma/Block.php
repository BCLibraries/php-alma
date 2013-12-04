<?php

namespace BCLib\Alma;

/**
 * Class Block
 * @package BCLib\Alma
 *
 * @property string type
 * @property string status
 * @property string code
 * @property string creation_date
 * @property string modification_date
 */
class Block
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
            case 'type':
            case 'status':
            case 'note':
                return (string) $this->_xml->$property;
            case 'code':
                return (string) $this->_xml->blockDefinitionId;
            case 'creation_date':
                return (string) $this->_xml->owneredEntity->creationDate;
            case 'modification_date':
                return (string) $this->_xml->owneredEntity->modificationDate;

        }
    }
}