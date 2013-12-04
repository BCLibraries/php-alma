## php-alma

Utilities for interacting with Alma Web Services in PHP. Currently read access is provided for two SOAP service: User Info services and Course services. 


### Connecting

To start:

```php
use BCLib\Alma\AlmaServices;

$soap_user = ''; // e.g. 'webservice'
$soap_institution = ''; // e.g. '01BC_INST'
$soap_pass = '';

AlmaServices::initialize($soap_user, $soap_institution, $soap_pass);
```

### Users 

To load a user:

```php

$user_services = AlmaServices::userInfoServices();

if ($user = $user_services->getUser('florinb')) {
    
    echo $user->last_name . ", " . $user->first_name . $user->middle_name . "\n";
    echo $user->email . "\n";
    
    if ($user->is_active) {
        echo "User is active.\n";
    } else {
        echo "User is not active.\n";
    }

    //Identifiers
    foreach ($user->identifiers as $id) {
        echo "\t" . $id->code . " is " . $id->value . "\n";
    }
    
    // Blocks
    foreach ($user->blocks as $block) {
        echo "\t" . $block->code . " " . $block->_type . " " . $block->status . " ";
        echo $block->creation_date . " " . $block->modification_date . "\n";
    }
}
```

See *user-demo.php* for a full example.

### Courses

To load a course:

```php
$course_services = AlmaServices::courseServices();

if ($courses = $course_services->getCourses('AD100', '03', 0, 10)) {
    foreach ($courses as $course) {

        echo $course->identifier . "\n";
        echo $course->name . "\n";

        foreach ($course->reading_lists as $list) {
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
```

Future plans:
* error handling
* tests
* Composer-ize