<?php

namespace BCLib\Alma;

require_once 'XMLLoadingTest.php';

class UserTest extends XMLLoadingTest
{
    public function testFieldsWork()
    {
        $id = $this->getMockBuilder('\BCLib\Alma\Identifier')
            ->disableOriginalConstructor()
            ->getMock();

        $block = $this->getMockBuilder('\BCLib\Alma\Block')
            ->disableOriginalConstructor()
            ->getMock();
        $user = new User($id, $block);

        $user->load($this->_getExampleXML('user-details-response-01.xml'));

        $this->assertEquals('Boyd', $user->last_name);
        $this->assertEquals('Lamont', $user->first_name);
        $this->assertEquals('Scott', $user->middle_name);
        $this->assertEquals('LamontEBoyd@exammple.com', $user->email);
        $this->assertTrue($user->is_active);
        $this->assertEquals('08', $user->group_code);
    }

    public function testIdentifiersLoaded()
    {
        $id = $this->getMockBuilder('\BCLib\Alma\Identifier')
            ->disableOriginalConstructor()
            ->getMock();

        $block = $this->getMockBuilder('\BCLib\Alma\Block')
            ->disableOriginalConstructor()
            ->getMock();

        $user = new User($id, $block);

        $user->load($this->_getExampleXML('user-details-response-01.xml'));

        $this->assertEquals(2, count($user->identifiers));
        $this->assertInstanceOf('\BCLib\Alma\Identifier', $user->identifiers[0]);
        $this->assertInstanceOf('\BCLib\Alma\Identifier', $user->identifiers[1]);
    }

    public function testBlocksLoaded()
    {
        $id = $this->getMockBuilder('\BCLib\Alma\Identifier')
            ->disableOriginalConstructor()
            ->getMock();

        $block = $this->getMockBuilder('\BCLib\Alma\Block')
            ->disableOriginalConstructor()
            ->getMock();

        $user = new User($id, $block);

        $user->load($this->_getExampleXML('user-details-response-01.xml'));

        $this->assertEquals(2, count($user->blocks));
        $this->assertInstanceOf('\BCLib\Alma\Block', $user->blocks[0]);
        $this->assertInstanceOf('\BCLib\Alma\Block', $user->blocks[1]);
    }
}