<?php
namespace Yandex\Detector;

/**
 * Class Response
 * @package Yandex\Detector
 * @see http://api.yandex.ru/detector/doc/dg/concepts/detector-response.xml
 * @author Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license The MIT License (MIT)
 */
class Response
{
    const DEVICE_CLASS_IPHONE = 'iphoneos';
    const DEVICE_CLASS_ANDROID = 'android';
    /**
     * @var \SimpleXMLElement
     */
    protected $_xmlSource;
    /**
     * @var array
     */
    protected $_data = array();

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->_xmlSource = $xml;
        $this->_data = json_decode(json_encode($xml), true);
    }

    /**
     * Исходные данные
     * @return \SimpleXMLElement
     */
    public function getSource()
    {
        return $this->_xmlSource;
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
        $result = isset($this->_data['name']) && !empty($this->_data['name']) ? (string)$this->_data['name'] : null;
        return $result;
    }

    /**
     * Название производителя устройства
     * @return null|string
     */
    public function getVendor()
    {
        $result = isset($this->_data['vendor']) && !empty($this->_data['vendor']) ? (string)$this->_data['vendor'] : null;
        return $result;
    }

    /**
     * Описание разновидности платформы и версии приложения – сокращенное
     * @return null|string
     */
    public function getDeviceClass()
    {
        $result = isset($this->_data['device-class']) && !empty($this->_data['device-class']) ? (string)$this->_data['device-class'] : null;
        return $result;
    }

    /**
     * Описание разновидности платформы и версии приложения – полное
     * @return null|string
     */
    public function getDeviceClassDesc()
    {
        $result = isset($this->_data['device-class-desc']) && !empty($this->_data['device-class-desc']) ? (string)$this->_data['device-class-desc'] : null;
        return $result;
    }

    /**
     * Размеры основного экрана устройства (в пикселах)
     * @return null|int
     */
    public function getScreenWidth()
    {
        $result = isset($this->_data['screenx']) && !empty($this->_data['screenx']) ? (int)$this->_data['screenx'] : null;
        return $result;
    }

    /**
     * Размеры основного экрана устройства (в пикселах)
     * @return null|int
     */
    public function getScreenHeight()
    {
        $result = isset($this->_data['screeny']) && !empty($this->_data['screeny']) ? (int)$this->_data['screeny'] : null;
        return $result;
    }
}