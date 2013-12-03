<?php

namespace BCLib\Alma;

require_once 'XMLLoadingTest.php';

class CourseServicesTest extends XMLLoadingTest
{
    public function testSearchCourseQueryCorrect()
    {
        $soap_args = array('arg0' => 'searchableId=BI110.0x', 'arg1' => 1, 'arg2' => 12);

        $soap_client = $this->getMockBuilder('\BCLib\Alma\AlmaSoapClient')
            ->disableOriginalConstructor()
            ->getMock();
        $soap_client->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('searchCourseInformation'),
                $this->equalTo($soap_args)
            );

        $section_proto = $this->getMockBuilder('\BCLib\Alma\Section')
            ->disableOriginalConstructor()
            ->getMock();

        $services = new CourseServices($soap_client, $section_proto);

        $services->getCourse('BI110.0x', 1, 12);
    }

    public function testSearchCoursesNoSectionQueryCorrect()
    {
        $soap_args = array('arg0' => 'code=BI110', 'arg1' => 1, 'arg2' => 12);

        $soap_client = $this->getMockBuilder('\BCLib\Alma\AlmaSoapClient')
            ->disableOriginalConstructor()
            ->getMock();
        $soap_client->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('searchCourseInformation'),
                $this->equalTo($soap_args)
            );

        $section_proto = $this->getMockBuilder('\BCLib\Alma\Section')
            ->disableOriginalConstructor()
            ->getMock();

        $services = new CourseServices($soap_client, $section_proto);

        $services->getCourses('BI110', null, 1, 12);
    }

    public function testSearchCoursesWithSectionQueryCorrect()
    {
        $soap_args = array('arg0' => 'code=BI110 and section=01', 'arg1' => 1, 'arg2' => 12);

        $soap_client = $this->getMockBuilder('\BCLib\Alma\AlmaSoapClient')
            ->disableOriginalConstructor()
            ->getMock();
        $soap_client->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('searchCourseInformation'),
                $this->equalTo($soap_args)
            );

        $section_proto = $this->getMockBuilder('\BCLib\Alma\Section')
            ->disableOriginalConstructor()
            ->getMock();

        $services = new CourseServices($soap_client, $section_proto);

        $services->getCourses('BI110', '01', 1, 12);
    }

    public function testSearchCourseWithNoLimitsDefault()
    {
        $soap_args = array('arg0' => 'code=BI110 and section=01', 'arg1' => 0, 'arg2' => 10);

        $soap_client = $this->getMockBuilder('\BCLib\Alma\AlmaSoapClient')
            ->disableOriginalConstructor()
            ->getMock();
        $soap_client->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('searchCourseInformation'),
                $this->equalTo($soap_args)
            );

        $section_proto = $this->getMockBuilder('\BCLib\Alma\Section')
            ->disableOriginalConstructor()
            ->getMock();

        $services = new CourseServices($soap_client, $section_proto);

        $services->getCourses('BI110', '01');
    }

    public function testReturnFalseOnError()
    {
        $soap_client = $this->getMockBuilder('\BCLib\Alma\AlmaSoapClient')
            ->disableOriginalConstructor()
            ->getMock();
        $soap_client->expects($this->any())
            ->method('lastError')
            ->will($this->returnValue('An error has occurred'));

        $section_proto = $this->getMockBuilder('\BCLib\Alma\Section')
            ->disableOriginalConstructor()
            ->getMock();

        $services = new CourseServices($soap_client, $section_proto);

        $this->assertFalse($services->getCourses('BI110'));
        $this->assertEquals('An error has occurred', $services->lastError());
    }

    public function testCorrectNumberOfSectionsReturned()
    {
        $xml = $this->_getExampleXML('course-response-01.xml');

        $soap_client = $this->getMockBuilder('\BCLib\Alma\AlmaSoapClient')
            ->disableOriginalConstructor()
            ->getMock();
        $soap_client->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($xml));
        $soap_client->expects($this->any())
            ->method('lastError')
            ->will($this->returnValue(false));

        $section_proto = $this->getMockBuilder('\BCLib\Alma\Section')
            ->disableOriginalConstructor()
            ->getMock();

        $services = new CourseServices($soap_client, $section_proto);
        $sections = $services->getCourse('BI110.0x');
        $this->assertEquals(3, count($sections));
        foreach ($sections as $section) {
            $this->assertInstanceOf('\BCLib\Alma\Section', $section);
        }


    }

    protected function _getExampleXML($file_name)
    {
        return simplexml_load_file(__DIR__ . "/../../examples/$file_name");
    }
}