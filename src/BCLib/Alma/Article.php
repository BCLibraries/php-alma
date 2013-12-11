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
class Article extends Citation implements \JsonSerializable
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
        }

        throw new \Exception("$property is not a valid Article property");
    }

    public function jsonSerialize()
    {
        $article = parent::jsonSerialize();
        $article->title = $this->title;
        $article->article_title = $this->article_title;
        $article->journal_title = $this->journal_title;
        $article->volume = $this->volume;
        $article->issue = $this->issue;
        $article->issn = $this->issn;
        return $article;
    }
}
