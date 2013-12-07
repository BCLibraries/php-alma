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
                $result = $this->_xml->xpath(('xb:'.$property));
                return (string) $result[0];
            case 'code':
                $result = $this->_xml->xpath(('xb:blockDefinitionId'));
                return (string) $result[0];
            case 'creation_date':
                $result = $this->_xml->xpath(('xb:owneredEntity/xb:creationDate'));
                return (string) $result[0];
            case 'modification_date':
                $result = $this->_xml->xpath(('xb:owneredEntity/xb:modificationDate'));
                return (string) $result[0];
        }
    }
}