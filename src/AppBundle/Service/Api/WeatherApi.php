<?php

namespace AppBundle\Service\Api;

use Monolog\Logger;

abstract class WeatherApi {

    private $endpoint;
    private $key;

    /**
     * @var Logger
     */
    protected $logger;

    function __construct($endpoint, $key, Logger $logger) {
        $this->endpoint = $endpoint;
        $this->key = $key;
        $this->logger = $logger;
    }

    function call($method, $params) {
        $url = $this->endpoint . "?appid=" . $this->key . "&" . $params;

        // Call
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($curl);
        
        if ($result === false) {
            $this->logger->error("No response from weather api", [
                "url" => $url
            ]);
            return null;
        }

        return json_decode($result);
    }

}
