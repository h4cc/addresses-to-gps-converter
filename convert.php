<?php

require_once(__DIR__.'/vendor/autoload.php');

define("FILE_FROM", $argv[1]);
define("FILE_TO", $argv[2]);
define("SLEEP_BETWEEN_SECONDS", 3);

// Read all addresses from FILE_FROM
echo "Reading ".FILE_FROM." ...\n";
$addresses = array_filter(explode("\n", file_get_contents(FILE_FROM)), 'trim');

// Ask remote service for GPS.
$geocoder = new \Geocoder\Provider\GoogleMaps(new \Ivory\HttpAdapter\CurlHttpAdapter());
$locations = array_map(function($address) use($geocoder) {

    sleep(SLEEP_BETWEEN_SECONDS);

    $ret = [$address, 0.0, 0.0]; // lat, lon

    $result = $geocoder->limit(1)->geocode($address);

    if($result->count() >= 0) {
        $first = $result->first();
        $coords = $first->getCoordinates();
        $ret = [
            $address,
            $coords->getLatitude(),
            $coords->getLongitude(),
        ];
    }

    $ret = implode(";", $ret);
    echo $ret, "\n";
    return $ret;

}, $addresses);


// Write found locations to FILE_TO
echo "Writing ".FILE_TO." ...\n";
file_put_contents(FILE_TO, implode("\n", $locations));

