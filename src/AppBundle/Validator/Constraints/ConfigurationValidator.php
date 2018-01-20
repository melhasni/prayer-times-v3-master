<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\Configuration;

class ConfigurationValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {

        if (
                $value->getSourceCalcul() === Configuration::SOURCE_API &&
                $value->getPrayerMethod() === Configuration::METHOD_CUSTOM
        ) {
            if (empty($value->getFajrDegree()) || empty($value->getIshaDegree())) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }

}
