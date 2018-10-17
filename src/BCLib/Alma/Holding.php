<?php

namespace BCLib\Alma;

/**
 * Class Holding
 * @package BCLib\Alma
 *
 * @property string institution
 * @property string library
 * @property string location
 * @property string call_number
 * @property string availability
 */
class Holding
{
    /**
     * @var \File_MARC_Data_Field
     */
    protected $_ava_field;

    public function load(\File_MARC_Data_Field $ava_field)
    {
        $this->_ava_field = $ava_field;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'institution':
                return $this->_ava_field->getSubfield('a')->getData();
            case 'library':
                return $this->_ava_field->getSubfield('b')->getData();
            case 'location':
                return $this->_ava_field->getSubfield('c')->getData();
            case 'call_number':
                return $this->_ava_field->getSubfield('d')->getData();
            case 'availability':
                return $this->_ava_field->getSubfield('e')->getData();
            default:
                throw new \Exception("$name is not a valid holdings field");
        }
    }

    public function jsonSerialize()
    {
        $holding = new \stdClass();
        $holding->institution = $this->institution;
        $holding->library = $this->library;
        $holding->location = $this->location;
        $holding->call_number = $this->call_number;
        $holding->availability = $this->availability;
        return $holding;
    }

} 