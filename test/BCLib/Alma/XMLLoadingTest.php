<?php

namespace BCLib\Alma;

/**
 * Class XMLLoadingTest
 * @package BCLib\Alma
 *
 * Since many of our tests involve loading SimpleXML objects, I thought I'd
 * write a convenience class that handles loading.
 */
abstract class XMLLoadingTest extends \PHPUnit_Framework_TestCase
{
    protected function _getExampleXML($file_name)
    {
        return simplexml_load_file(__DIR__ . "/../../examples/$file_name");
    }

} 