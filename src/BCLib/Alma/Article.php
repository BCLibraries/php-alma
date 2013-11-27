<?php

namespace BCLib\Alma;

/**
 * Class Article
 * @package BCLib\Alma
 *
 * @property string issn
 * @property string article_title
 * @property string journal_title
 * @property string volume
 * @property string issue
 * @property string url
 */
class Article extends Citation
{
    public function __get($property)
    {
        $value = parent::__get($property);
        if (!is_null($value)) {
            return $value;
        }

        switch ($property) {
            case 'title':
                return (string) $this->_xml->metadata->article_title;
            case 'article_title':
            case 'journal_title':
            case 'volume':
            case 'issue':
                return (string) $this->_xml->metadata->$property;
            case 'issn':
                return (string) $this->_xml->metadata->ISSN;
            case 'url':
                return (string) $this->_xml->metadata->URL;
        }

        throw new \Exception("$property is not a valid Article property");
    }
}
