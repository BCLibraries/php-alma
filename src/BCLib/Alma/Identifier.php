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
    /**
     * @var \SimpleXMLElement
     */
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
                $result = $this->_xml->xpath('xb:'.$property);
                return (string) $result[0];
            case ('name'):
                $name = '';
                if (isset($this->_id_types[(string) $this->type])) {
                    $name = $this->_id_types[(string) $this->type];
                }
                return $name;
        }
    }
}