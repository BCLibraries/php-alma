<?php

namespace BCLib\Alma;

class CitationFactory
{
    /**
     * @var Book
     */
    protected $_book_prototype;

    /**
     * @var Article
     */
    protected $_article_prototype;

    public function __construct(Book $book_prototype, Article $article_prototype)
    {
        $this->_book_prototype = $book_prototype;
        $this->_article_prototype = $article_prototype;
    }

    public function createCitation(\SimpleXMLElement $xml)
    {
        switch ($xml->type) {
            case 'Physical Book':
            case 'Electronic Book':
            return clone $this->_book_prototype;
            case 'Physical Article':
            case 'Electronic Article':
                return clone $this->_article_prototype;
            default:
                throw new \Exception($xml->type . ' is not a valid citation type');
        }
    }
} 