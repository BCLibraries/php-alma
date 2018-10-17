<?php

namespace BCLib\Alma;

use JsonSerializable;

/**
 * Class Book
 * @package BCLib\Alma
 *
 * @property string publisher
 * @property string isbn
 * @property string edition
 * @property string title
 */
class Book extends Citation implements JsonSerializable
{
    public function __get($property)
    {
        $value = parent::__get($property);
        if ($value !== null) {
            return $value;
        }

        switch ($property) {
            case 'publisher':
            case 'edition':
            case 'title':
                return (string) $this->_xml->metadata->$property;
            case 'isbn':
                return (string) $this->_xml->metadata->ISBN;
        }

        throw new \Exception("$property is not a valid Book property");
    }

    public function jsonSerialize()
    {
        $book = parent::jsonSerialize();
        $book->title = $this->title;
        $book->publisher = $this->publisher;
        $book->edition = $this->edition;
        $book->isbn = $this->edition;
        return $book;
    }
}