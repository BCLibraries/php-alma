<?php

use BCLib\Alma\AlmaServices;

require_once __DIR__ . '/vendor/autoload.php';

$soap_user = ''; // e.g. 'webservice'
$soap_institution = ''; // e.g. '01BC_INST'
$soap_pass = '';
$wsdl = __DIR__ . '/UserWebServices.xml';

// First create the SOAP client.
AlmaServices::initialize($soap_user, $soap_pass, $soap_institution);
$course_services = AlmaServices::courseServices();

if ($courses = $course_services->getCourses('AD100', '03', 0, 10)) {
    foreach ($courses as $course) {

        echo $course->identifier . "\n";
        echo $course->name . "\n";

        foreach ($course->complete_lists as $list) {
            echo "\t" . $list->identifier . "\n";
            echo "\t" . $list->name . "\n";

            foreach ($list->citations as $citation) {
                echo "\t\t" . $citation->title . "\n";
                echo "\t\t" . $citation->author . "\n";
                echo "\t\t" . $citation->open_url . "\n";
            }
        }
    }
} else {
    echo $course_services->lastError()->message;
}