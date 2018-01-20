<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PrayerTransformer implements DataTransformerInterface {

    public function transform($values) {
        return [
            "fajr" => $values[0],
            "zuhr" => $values[1],
            "asr" => $values[2],
            "maghrib" => $values[3],
            "isha" => $values[4]
        ];
    }

    public function reverseTransform($prayerIssue) {
        return $prayerIssue;
    }

}
