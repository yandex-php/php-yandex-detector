<?php
namespace Yandex\Detector;

/**
 * Class Response
 * @package Yandex\Detector
 * @see http://api.yandex.ru/detector/doc/dg/concepts/detector-response.xml
 * @license The MIT License (MIT)
 */
class Response
{
    const DEVICE_CLASS_IPHONE = 'iphoneos';
    const DEVICE_CLASS_ANDROID = 'android';
    /**
     * @var \SimpleXMLElement
     */
    protected $xmlSource;
    /**
     * @var array
     */
    protected $data = array();

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->xmlSource = $xml;
        $this->data = json_decode(json_encode($xml), true);
    }

    /**
     * Исходные данные
     * @return \SimpleXMLElement
     */
    public function getSource()
    {
        return $this->xmlSource;
    }

    /**
     * @return bool
     */
    public function isIphone()
    {
        return $this->getDeviceClass() == self::DEVICE_CLASS_IPHONE;
    }

    /**
     * @return bool
     */
    public function isAndroid()
    {
        return $this->getDeviceClass() == self::DEVICE_CLASS_ANDROID;
    }

    /**
     * Название модели устройства
     * @return null|string
     */
    public function getName()
    {
        $result = isset($this->data['name']) && !empty($this->data['name']) ? (string) $this->data['name'] : null;

        return $result;
    }

    /**
     * Название производителя устройства
     * @return null|string
     */
    public function getVendor()
    {
        $result = isset($this->data['vendor']) && !empty($this->data['vendor']) ? (string) $this->data['vendor'] : null;

        return $result;
    }

    /**
     * Описание разновидности платформы и версии приложения – сокращенное
     * @return null|string
     */
    public function getDeviceClass()
    {
        $result = isset($this->data['device-class']) && !empty($this->data['device-class']) ? (string) $this->data['device-class'] : null;

        return $result;
    }

    /**
     * Описание разновидности платформы и версии приложения – полное
     * @return null|string
     */
    public function getDeviceClassDesc()
    {
        $result = isset($this->data['device-class-desc']) && !empty($this->data['device-class-desc']) ? (string) $this->data['device-class-desc'] : null;

        return $result;
    }

    /**
     * Размеры основного экрана устройства (в пикселах)
     * @return null|int
     */
    public function getScreenWidth()
    {
        $result = isset($this->data['screenx']) && !empty($this->data['screenx']) ? (int) $this->data['screenx'] : null;

        return $result;
    }

    /**
     * Размеры основного экрана устройства (в пикселах)
     * @return null|int
     */
    public function getScreenHeight()
    {
        $result = isset($this->data['screeny']) && !empty($this->data['screeny']) ? (int) $this->data['screeny'] : null;

        return $result;
    }
}
