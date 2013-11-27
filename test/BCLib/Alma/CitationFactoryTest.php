<?php

namespace BCLib\Alma;

class CitationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CitationFactory
     */
    protected $_factory;

    public function setUp()
    {
        $book = \Mockery::mock('\BCLib\Alma\Book');
        $article = \Mockery::mock('\BCLib\Alma\Article');
        $this->_factory = new CitationFactory($book, $article);
    }

    public function testPhysicalArticleConstructionWorks()
    {
        $xml = $this->_getExampleXML('article-01.xml');
        $factory = $this->_factory->createCitation($xml);
        $this->assertInstanceOf('\BCLib\Alma\Article', $factory);
    }

    public function testElectronicArticleConstructionWorks()
    {
        $xml = $this->_getExampleXML('article-02.xml');
        $factory = $this->_factory->createCitation($xml);
        $this->assertInstanceOf('\BCLib\Alma\Article', $factory);
    }

    public function testBookConstructionWorks()
    {
        $xml = $this->_getExampleXML('book-01.xml');
        $factory = $this->_factory->createCitation($xml);
        $this->assertInstanceOf('\BCLib\Alma\Book', $factory);
    }

    protected function _getExampleXML($file_name)
    {
        return simplexml_load_file(__DIR__ . "/../../examples/$file_name");
    }
}
 