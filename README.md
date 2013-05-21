php-alma
========

Utilities for interacting with Alma Web Services in PHP. 

To connect:

```php
$soap_user = ''; // e.g. 'webservice'
$soap_institution = ''; // e.g. '01BC_INST'
$soap_pass = '';
$wsdl = __DIR__ . '/UserWebServices.xml';

$client = new \BCLib\Alma\AlmaSoapClient($soap_user, $soap_institution, $soap_pass, $wsdl);
```
To load a user:

```php
$user = new \BCLib\Alma\User($client);
if ($user->load('88779385'))
{
    echo $user->lastName() . ", " . $user->firstName() . $user->middleName() . "\n";
}
else
{
    echo $user->lastError()->message . "\n";
}
```

See *user-demo.php* for a full example.

Future plans:
* error handling
* tests
* Composer-ize