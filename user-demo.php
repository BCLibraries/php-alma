<?php

use BCLib\Alma\AlmaServices;

require_once __DIR__ . '/vendor/autoload.php';

$soap_user = ''; // e.g. 'webservice'
$soap_institution = ''; // e.g. '01BC_INST'
$soap_pass = '';
$wsdl = __DIR__ . '/UserWebServices.xml';

$user_id = ''; // User ID used by Alma.

// Optional map of user group codes to names.
$user_groups = array(
    '01' => 'BC Undergraduate',
    '35' => 'BLC Navigator (NRE)',
    '03' => 'BC Master\'s',
    '04' => 'BC Doctoral',
    '05' => 'BC Law Student',
    '06' => 'BC Faculty',
    '07' => 'BC Law Faculty',
    '08' => 'BC Staff'
);

// Optional map of identifier codes to names.
$id_types = array(
    '01' => 'Eagle ID',
    '02' => 'BC UserID',
    '00' => 'System number',
    '04' => 'Social Security number',
    '05' => 'ID Card Mag Stripe',
    '03' => 'NOTIS barcode number',
);

// First create the SOAP client.
AlmaServices::initialize($soap_user, $soap_institution, $soap_pass);
$user_services = AlmaServices::userInfoServices($wsdl);

if ($user = $user_services->getUser($user_id)) {
    echo $user->lastName() . ", " . $user->firstName() . $user->middleName() . "\n";
    echo $user->email() . "\n";
    echo $user->groupName($user_groups) . " ";
    echo $user->groupCode() . "\n";

    if ($user->isActive()) {
        echo "User is active.\n";
    }

    echo "Identifiers\n";
    foreach ($user->identifiers($id_types) as $id) {
        echo "\t" . $id->value . " " . $id->name . " " . $id->code . "\n";
    }

    echo "Blocks\n";
    foreach ($user->blocks() as $block) {
        echo "\t" . $block->code . " " . $block->type . " " . $block->status . " ";
        echo $block->creation_date . " " . $block->modification_date . "\n";
    }
} else {
    echo $user_services->lastError()->message . "\n";
}