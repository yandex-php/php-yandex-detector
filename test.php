<?php
require_once 'autoload.php';

$api = new \Yandex\Detector\Api([CURLOPT_TIMEOUT => 30, CURLOPT_CONNECTTIMEOUT => 30]);

$api
    ->setUserAgent('Alcatel-CTH3/1.0 UP.Browser/6.2.ALCATEL MMP/1.0')
    ->setWapProfile('http://www-ccpp-mpd.alcatel.com/files/ALCATEL-CTH3_MMS10_1.0.rdf')
    ->load();
$response = $api->getResponse();

var_dump($response->getName());