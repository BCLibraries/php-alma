<?php

namespace BCLib\Alma;

class ReadingListTest extends \PHPUnit_Framework_TestCase
{
    public function testFieldsWork()
    {
        $factory = \Mockery::mock('\BCLib\Alma\CitationFactory');
        $list = new ReadingList($factory);
        $list->load($this->_getExampleXML('reading-list-01.xml'));

        $this->assertEquals('9696548640001021', $list->identifier);
        $this->assertEquals('BI110.01-Jones', $list->code);
        $this->assertEquals('Aging Well-Jones', $list->name);
        $this->assertEquals('Inactive', $list->status);
    }

    public function testCitationsAreSet()
    {
        $citation1 = \Mockery::mock('\BCLib\Alma\Citation');
        $citation1->shouldReceive('load')->withAnyArgs();

        $citation2 = \Mockery::mock('\BCLib\Alma\Citation');
        $citation2->shouldReceive('load')->withAnyArgs();

        $citation3 = \Mockery::mock('\BCLib\Alma\Citation');
        $citation3->shouldReceive('load')->withAnyArgs();

        $factory = \Mockery::mock('\BCLib\Alma\CitationFactory');
        $factory->shouldReceive('createCitation')->times(3)->andReturn($citation1, $citation2, $citation3);
        $list = new ReadingList($factory);

        $xml = $this->_getExampleXML('reading-list-01.xml');
        $list->load($xml);

        $this->assertEquals(array($citation1, $citation2, $citation3), $list->citations);
    }

    protected function _getExampleXML($file_name)
    {
        return simplexml_load_file(__DIR__ . "/../../examples/$file_name");
    }
}
 