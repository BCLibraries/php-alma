<?php

namespace BCLib\Alma;

class BookTest extends \PHPUnit_Framework_TestCase
{
    protected $_book;

    public function setUp()
    {
        $this->_book = new Book();
    }

    public function testFieldsWork()
    {
        $this->_book->load($this->_getExampleXML('book-01.xml'));

        $this->assertEquals('8580038990001021', $this->_book->identifier);
        $this->assertEquals('Complete', $this->_book->status);
        $this->assertEquals('Physical Book', $this->_book->type);
        $this->assertEquals('0060012781 (trade paper)', $this->_book->isbn);
        $this->assertEquals('Fake Title', $this->_book->title);
        $this->assertEquals('2002.', $this->_book->year);
        $this->assertEquals('Bob', $this->_book->additional_person_name);
        $this->assertEquals('New York :', $this->_book->place_of_publication);
        $this->assertEquals('99134834650001021', $this->_book->mms_id);
        $this->assertEquals('Bourdain', $this->_book->author);
        $this->assertEquals('1', $this->_book->chapter);
        $this->assertEquals('12-34', $this->_book->pages);
        $this->assertEquals('This is not a real book.', $this->_book->note);
        $this->assertEquals(
            'http://bc-primo.hosted.exlibrisgroup.com/openurl/BCL/services_page?dscnt=1&vid=services_page&u.ignore_date_coverage=true&rft.mms_id=99134834650001021',
            $this->_book->open_url
        );
        $this->assertEquals('http://catdir.loc.gov/catdir/description/hc042/2002023507.html', $this->_book->url);
    }

    protected function _getExampleXML($file_name)
    {
        return simplexml_load_file(__DIR__ . "/../../examples/$file_name");
    }
}
 