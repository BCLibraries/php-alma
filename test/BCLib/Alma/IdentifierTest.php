<?php

namespace BCLib\Alma;

require_once 'XMLLoadingTest.php';

class IdentifierTest extends XMLLoadingTest
{
    public function testFieldsWork()
    {
        $id = new Identifier(array());
        $id->load($this->_getExampleXML('identifier-01.xml'));

        $this->assertEquals('12345678', $id->value);
        $this->assertEquals('01', $id->type);
    }

    public function testIdNameWorks()
    {
        $identifiers = array('00' => 'Name 0', '01' => 'Name 1', '02' => 'Name 2');

        $id = new Identifier($identifiers);
        $id->load($this->_getExampleXML('identifier-01.xml'));
        $this->assertEquals('Name 1', $id->name);
    }

}
 