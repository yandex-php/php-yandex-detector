API для работы с сервисом Яндекс.Детектор
=========================================

API Яндекс.Детектор предоставляет возможность определения модели и характеристик мобильного устройства пользователя
 сайта по заголовкам HTTP-запросов, передаваемых браузером его устройства.


Пример
------
```php
<?php
$api = new \Yandex\Detector\Api();

try {
    // Взять параметры из заголовков, переданных серверу
    $api->load();

    // Или указать параметры вручную
    $api
        ->reset()
        ->setUserAgent('Alcatel-CTH3/1.0 UP.Browser/6.2.ALCATEL MMP/1.0')
        ->setWapProfile('http://www-ccpp-mpd.alcatel.com/files/ALCATEL-CTH3_MMS10_1.0.rdf')
        ->load();
} catch (\Yandex\Detector\Exception $ex) {
}

$response = $api->getResponse();
if (!$response) {
    echo 'Телефон не определен';
} else {
    echo $response->getName();
    echo $response->getVendor();

    if ($response->isIphone()) {
    } elseif ($response->isAndroid()) {
    }

    echo $response->getScreenWidth() . 'x' . $response->getScreenHeight();
}
```