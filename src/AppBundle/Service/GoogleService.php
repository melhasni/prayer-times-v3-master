<?php

namespace AppBundle\Service;

use AppBundle\Service\Api\GoogleApi;
use Monolog\Logger;
use AppBundle\Exception\GooglePositionException;

class GoogleService extends GoogleApi {

    const PATH_GEOCODE = "/geocode/json";

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Get longitude and latitude
     * @param string $address
     * @return array
     * @throws GooglePositionException
     */
    function getPosition($address) {
        $url = self::PATH_GEOCODE . "?address=$address";
        $res = $this->get($url);

        if ($res instanceof \stdClass && isset($res->results[0]->geometry->location)) {
            return $res->results[0]->geometry->location;
        }
        
        $this->logger->error("Impossible de localiser l'adresse $address");
        throw new GooglePositionException();
    }

    /**
     * @param Logger $logger
     */
    function setLogger(Logger $logger) {
        $this->logger = $logger;
    }
}
