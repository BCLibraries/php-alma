<?php

namespace BCLib\Alma;

require_once 'XMLLoadingTest.php';

class BlockTest extends XMLLoadingTest
{
    public function testFieldsWork()
    {
        $block = new Block();
        $block->load($this->_getExampleXML('block-01.xml'));
        $this->assertEquals('General', $block->type);
        $this->assertEquals('CR', $block->code);
        $this->assertEquals('Active', $block->status);
        $this->assertEquals('20131203201037', $block->creation_date);
        $this->assertEquals('20131203201127', $block->modification_date);
    }
}
 