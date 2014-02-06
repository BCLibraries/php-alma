<?php

use BCLib\Alma;

require_once __DIR__ . '/../vendor/autoload.php';

$soap_user = ''; // e.g. webservice
$soap_institution = ''; // e.g. 01BC_INST
$soap_pass = ''; // e.g. my_password

// Use a Doctrine cache, if desired. Null sets no cache.
// $cache = new \Doctrine\Common\Cache\ApcCache();
$cache = null;

Alma\AlmaServices::initialize($soap_user, $soap_pass, $soap_institution, $cache);
$service = Alma\AlmaServices::holdingsServices();

// Pass in an array of MMS IDs.
$bib_records = $service->getHoldings(
    array('99131822450001021', '99106869560001021', '99102603870001021', '99131514000001021')
);

foreach ($bib_records as $bib_record) {
    echo $bib_record->mms . "\n";

    // Returns a list of PEAR File_MARC_Field objects.
    foreach ($bib_record->getMARCField('245') as $marc_field) {
        echo $marc_field->getSubfield('a')->getData() . "\n";
    }

    foreach ($bib_record->holdings as $holding) {
        echo $holding->availability . "\n";
        echo $holding->call_number . "\n";
        echo $holding->institution . "\n";
        echo $holding->library . "\n";
        echo $holding->location . "\n";
    }
}