<?php

if (interface_exists('JsonSerializable', false)) {
    return;
}

/**
 * For PHP 5.3 compatibility
 *
 * Using json_encode() on objects in php-alma requires the JsonSerializable interface,
 * which is only available in PHP 5.4+. Most sites still use 5.3, so I've added this
 * hack to make it sort-of compatible.
 *
 * Be aware that json_encode will not return appropriate values in 5.3!
 *
 * @ignore
 */
interface JsonSerializable
{
    function jsonSerialize();
}