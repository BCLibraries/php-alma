<?php

namespace BCLib\Alma;

require_once 'XMLLoadingTest.php';

class ArticleTest extends XMLLoadingTest
{
    /**
     * @var Article
     */
    protected $_article;

    public function setUp()
    {
        $this->_article = new Article();
    }

    public function testFieldsWork()
    {
        $this->_article->load($this->_getExampleXML('article-01.xml'));

        $this->assertEquals('4016503630001021', $this->_article->identifier);
        $this->assertEquals('Complete', $this->_article->status);
        $this->assertEquals('Physical Article', $this->_article->type);
        $this->assertEquals('1234-5678', $this->_article->issn);
        $this->assertEquals('Fake Article', $this->_article->article_title);
        $this->assertEquals('Made-up Journal Name', $this->_article->journal_title);
        $this->assertEquals('12', $this->_article->volume);
        $this->assertEquals('2004', $this->_article->year);
        $this->assertEquals('Bob', $this->_article->additional_person_name);
        $this->assertEquals('New York :', $this->_article->place_of_publication);
        $this->assertEquals('9999270250001021', $this->_article->mms_id);
        $this->assertEquals('Alice', $this->_article->author);
        $this->assertEquals('1', $this->_article->chapter);
        $this->assertEquals('12-34', $this->_article->pages);
        $this->assertEquals('This is not a real article', $this->_article->note);
        $this->assertEquals(
            'http://bc-primo.hosted.exlibrisgroup.com/openurl/BCL/services_page?dscnt=1&vid=services_page&u.ignore_date_coverage=true&rft.mms_id=9999270250001021',
            $this->_article->open_url
        );
        $this->assertEquals('http://irm.bc.edu/reserves/en010/elli/en01005.pdf', $this->_article->url);
    }

    protected function _getExampleXML($file_name)
    {
        return simplexml_load_file(__DIR__ . "/../../examples/$file_name");
    }
}
 