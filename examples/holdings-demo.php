<?php

require_once __DIR__ . '/vendor/autoload.php';

// Use a valid MMS ID from your collection.
$mms = '99103130010001021';

// Your Alma apikey.
$apikey = 'YOURAPIKEY';

// The base URL of your Alma install.
$base_url = 'https://api-na.hosted.exlibrisgroup.com/almaws/';

$client = \BCLib\Alma\AlmaServices::bibServices($apikey, $base_url);

$holding_result = $client->listHoldings($mms);
foreach ($holding_result->holdings as $holding) {
    echo $holding->call_number . "\n";
}

$first_holding_id = $holding_result->holdings[0]->holding_id;
$item_result = $client->listItems($first_holding_id, $mms);
foreach ($item_result->items as $item) {
    echo "{$item->barcode} is at {$item->temp_library_desc}\n";
}