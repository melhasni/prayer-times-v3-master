<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Configuration extends Constraint {

    public $message = 'form.configuration.custom_prayer_method';

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}
