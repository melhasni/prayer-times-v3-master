<?php

namespace AppBundle\Service\Api;

abstract class GoogleApi {

    private $endpoint;
    private $key;

    function __construct($endpoint, $key) {
        $this->endpoint = $endpoint;
        $this->key = $key;
    }

    function call($method, $path, $body = null) {
        $url = $this->endpoint . $path . "&key=" . $this->key;

        if ($body) {
            $body = json_encode($body);
        } else {
            $body = "";
        }

        // Call
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        if ($body) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        $result = curl_exec($curl);

        if ($result === FALSE) {
            return NULL;
        }

        return json_decode($result);
    }

    function get($path) {
        return $this->call("GET", $path);
    }

    function put($path, $body) {
        return $this->call("PUT", $path, $body);
    }

    function post($path, $body) {
        return $this->call("POST", $path, $body);
    }

    function delete($path) {
        return $this->call("DELETE", $path);
    }

}
