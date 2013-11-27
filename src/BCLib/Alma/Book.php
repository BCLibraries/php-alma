<?php

namespace BCLib\Alma;

/**
 * Class Book
 * @package BCLib\Alma
 *
 * @property string publisher
 * @property string isbn
 * @property string edition
 * @property string title
 */
class Book extends Citation
{
    public function __get($property)
    {
        $value =  parent::__get($property);
        if (! is_null($value)) {
            return $value;
        }

        switch ($property) {
            case 'publisher':
            case 'isbn':
            case 'edition':
            case 'title':
                return (string) $this->_xml->metadata->$property;
        }

        throw new \Exception("$property is not a valid Book property");
    }
} 