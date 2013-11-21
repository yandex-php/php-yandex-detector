<?php
namespace Yandex\Detector;

/**
 * Class Api
 * @package Yandex\Detector
 * @see http://api.yandex.ru/detector/doc/dg/concepts/detector-request.xml
 * @author Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license The MIT License (MIT)
 */
class Api
{
    /**
     * @var array
     */
    protected $_filters = array();
    /**
     * @var \Yandex\Detector\Response
     */
    protected $_response;

    public function __construct()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        }
        if (isset($_SERVER['x-operamini-phone-ua'])) {
            $this->setXOperaMini($_SERVER['x-operamini-phone-ua']);
        }
    }

    /**
     * @param string $userAgent Версия браузера мобильного устройства
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->_filters['user-agent'] = (string)$userAgent;
        return $this;
    }

    /**
     * @param string $profile Ссылка на UAProf-файл, содержащий информацию о возможностях мобильного устройства (профиль)
     * @return self
     */
    public function setProfile($profile)
    {
        $this->_filters['profile'] = (string)$profile;
        return $this;
    }

    /**
     * @param string $wapProfile Ссылка на UAProf-файл, содержащий информацию о возможностях мобильного устройства (профиль)
     * @return self
     */
    public function setWapProfile($wapProfile)
    {
        $this->_filters['wap-profile'] = (string)$wapProfile;
        return $this;
    }

    /**
     * @param string $xWapProfile Ссылка на UAProf-файл, содержащий информацию о возможностях мобильного устройства (профиль)
     * @return self
     */
    public function setXWapProfile($xWapProfile)
    {
        $this->_filters['x-wap-profile'] = (string)$xWapProfile;
        return $this;
    }

    /**
     * @param string $xOperaMini Дополнительный заголовок, передаваемый браузером Opera Mini. Обычно содержит версию штатного браузера мобильного устройства
     * @return self
     */
    public function setXOperaMini($xOperaMini)
    {
        $this->_filters['x-operamini-phone-ua'] = (string)$xOperaMini;
        return $this;
    }

    /**
     * Очитстить все фильтры
     * @return self
     */
    public function reset()
    {
        $this->_filters = array();
        return $this;
    }

    /**
     * @throws \Yandex\Detector\Exception
     * @return self
     */
    public function load()
    {
        $apiUrl = 'http://phd.yandex.net/detect?';
        $query = http_build_query($this->_filters);
        $data = file_get_contents($apiUrl . $query);
        $xml = @simplexml_load_string($data);
        if (!($xml instanceof \SimpleXMLElement)) {
            throw new \Yandex\Detector\Exception(sprintf("Bad response: \"%s\"", $data));
        }
        if ($xml->getName() == 'yandex-mobile-info-error') {
            throw new \Yandex\Detector\Exception(sprintf("Error: %s", $xml));
        }
        $this->_response = new \Yandex\Detector\Response($xml);
        return $this;
    }

    /**
     * @return \Yandex\Detector\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }
}
