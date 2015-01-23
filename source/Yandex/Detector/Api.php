<?php
namespace Yandex\Detector;

/**
 * Class Api
 * @package Yandex\Detector
 * @see http://api.yandex.ru/detector/doc/dg/concepts/detector-request.xml
 * @license The MIT License (MIT)
 */
class Api
{
    /**
     * @var array
     */
    protected $curlOptions = array();
    /**
     * @var array
     */
    protected $filters = array();
    /**
     * @var \Yandex\Detector\Response
     */
    protected $response;

    public function __construct(array $curlOptions = array())
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        }
        if (isset($_SERVER['x-operamini-phone-ua'])) {
            $this->setXOperaMini($_SERVER['x-operamini-phone-ua']);
        }
        $this->curlOptions = $curlOptions;
    }

    /**
     * @param  string $userAgent Версия браузера мобильного устройства
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->filters['user-agent'] = (string)$userAgent;

        return $this;
    }

    /**
     * @param  string $profile Ссылка на UAProf-файл, содержащий информацию о возможностях мобильного устройства (профиль)
     * @return self
     */
    public function setProfile($profile)
    {
        $this->filters['profile'] = (string)$profile;

        return $this;
    }

    /**
     * @param  string $wapProfile Ссылка на UAProf-файл, содержащий информацию о возможностях мобильного устройства (профиль)
     * @return self
     */
    public function setWapProfile($wapProfile)
    {
        $this->filters['wap-profile'] = (string)$wapProfile;

        return $this;
    }

    /**
     * @param  string $xWapProfile Ссылка на UAProf-файл, содержащий информацию о возможностях мобильного устройства (профиль)
     * @return self
     */
    public function setXWapProfile($xWapProfile)
    {
        $this->filters['x-wap-profile'] = (string)$xWapProfile;

        return $this;
    }

    /**
     * @param  string $xOperaMini Дополнительный заголовок, передаваемый браузером Opera Mini. Обычно содержит версию штатного браузера мобильного устройства
     * @return self
     */
    public function setXOperaMini($xOperaMini)
    {
        $this->filters['x-operamini-phone-ua'] = (string)$xOperaMini;

        return $this;
    }

    /**
     * Очистить все фильтры
     * @return self
     */
    public function reset()
    {
        $this->filters = array();

        return $this;
    }

    /**
     * @throws \Yandex\Detector\Exception
     * @return self
     */
    public function load()
    {
        $data = $this->sendQuery();
        $xml = @simplexml_load_string($data);
        if (!($xml instanceof \SimpleXMLElement)) {
            throw new \Yandex\Detector\Exception(sprintf("Bad response: \"%s\"", $data));
        }
        if ($xml->getName() == 'yandex-mobile-info-error') {
            throw new \Yandex\Detector\Exception(sprintf("Error: %s", $xml));
        }
        $this->response = new \Yandex\Detector\Response($xml);

        return $this;
    }

    /**
     * @return \Yandex\Detector\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    protected function sendQuery()
    {
        $apiUrl = 'http://phd.yandex.net/detect?';
        $query = http_build_query($this->filters);

        $ch = curl_init($apiUrl . $query);
        if (!$ch) {
            throw new \Yandex\Detector\Exception("Can't initialize CURL");
        } else {
            $options = $this->curlOptions + array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_VERBOSE => false
                );
            curl_setopt_array($ch, $options);
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                throw new \Yandex\Detector\Exception(sprintf("Curl error: %s", $error));
            }
            curl_close($ch);
        }

        return $data;
    }
}
