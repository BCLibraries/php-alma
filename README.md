## php-alma

### DEPRECATED

This package uses the **deprecated** Alma SOAP API and has very limited support. For new projects, use the [php-alma-client](https://github.com/scriptotek/php-alma-client) package.

### Description

Utilities for interacting with Alma Web Services in PHP. Currently read access is provided for three SOAP service: User Info services, Course services, and Holdings services. 

### Installation

`php-alma` uses the [Composer](http://getcomposer.org/) dependency management system. To install 

1. If you haven't already, [install `composer.phar`](http://getcomposer.org/doc/00-intro.md#installation-nix). To install `composer.phar` in the `/usr/bin` directory on Linux/OS X:
 
		sudo curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin

2. Create a `composer.json` file. The example below will install `php-alma`:


		{
            "name": "your-org/your-project",
            "description": "Describe your project",
            "license": "MIT",
            "repositories": [
                {
                    "type": "vcs",
                    "url": "https://github.com/BCLibraries/php-alma"
                },
                {
                    "url": "https://github.com/pear/File_MARC.git",
                    "type": "git"
                }
            ],
            "require": {
                "bclibraries/php-alma": "master",
                "pear/File_MARC": "*"
            },
            "minimum-stability": "dev"
        }
   
   Transitive composer installs don't work with PEAR repositories, so you'll have to specifically include the PEAR install in your `composer.json`.
    
3. Install using `composer.phar`:

		php composer.phar install


Composer will load all the required dependencies and create an `vendor/autoload.php` file to handle autoloading classes.

### Connecting

To start:

```php
use BCLib\Alma\AlmaServices;

require_once __DIR__.'/vendor/autoload.php';

$soap_user = ''; // e.g. 'webservice'
$soap_institution = ''; // e.g. '01BC_INST'
$soap_pass = '';

AlmaServices::initialize($soap_user, $soap_pass, $soap_institution);
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
```

### Holdings

Holdings uses REST services.

```php
// Your Alma apikey.
$apikey = 'YOURAPIKEY';

// The base URL of your Alma install.
$base_url = 'https://api-na.hosted.exlibrisgroup.com/almaws/';

$client = \BCLib\Alma\AlmaServices::bibServices($apikey, $base_url);

// Use a valid MMS ID from your collection.
$mms = '99103130010001021';

$holding_result = $client->listHoldings($mms);
foreach ($holding_result->holdings as $holding) {
    echo $holding->call_number . "\n";
}

$first_holding_id = $holding_result->holdings[0]->holding_id;
$item_result = $client->listItems($first_holding_id, $mms);
foreach ($item_result->items as $item) {
    echo "{$item->barcode} is at {$item->temp_library_desc}\n";
}
```

## License

See MIT-LICENSE