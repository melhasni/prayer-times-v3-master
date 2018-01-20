<?php

namespace AppBundle\Service;

use AppBundle\Service\Api\WeatherApi;
use AppBundle\Entity\Mosque;

class WeatherService extends WeatherApi {

    /**
     * get temperature of the mosque city, in celsius
     * @param Mosque $mosque
     * @return type
     */
    function getTemperature(Mosque $mosque) {
        $position = $mosque->getGpsCoordinates();
        $position = "lat=" . $position["lat"] . "&lon=" . $position["lon"];
        $res = $this->call("GET", $position);

        if ($res instanceof \stdClass && isset($res->main->temp)) {
            return number_format(round($res->main->temp - 273.15), 0);
        } else {
            $this->logger->error("Weather API Error", [
                'response' => $res
            ]);
        }

        return "";
    }

}
