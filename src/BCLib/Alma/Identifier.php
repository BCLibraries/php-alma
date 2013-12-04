<?php

namespace BCLib\Alma;

/**
 * Class Identifier
 * @package BCLib\Alma
 *
 * @property string value
 * @property string type
 * @property string name
 */
class Identifier
{
    protected $_xml;
    protected $_id_types;

    public function __construct(array $id_types)
    {
        $this->_id_types = $id_types;
    }

    public function load(\SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
    }

    public function __get($property)
    {
        switch ($property) {
            case ('value'):
            case ('type'):
                return (string) $this->_xml->$property;
            case ('name'):
                $name = '';
                if (isset($this->_id_types[(string) $this->_xml->type])) {
                    $name = $this->_id_types[(string) $this->_xml->type];
                }
                return $name;
        }
    }
}