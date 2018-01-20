<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MessageValidator extends ConstraintValidator
{
    const MAX_EANBLED_MESSAGE = 8;
    const MAX_MESSAGE = 20;

    public function validate($message, Constraint $constraint)
    {
        if ($message->mosque->getMessages()->count() >= self::MAX_MESSAGE) {
            $this->context->buildViolation($constraint->messageMaxReached)->addViolation();
            return;
        }

        if ($message->isEnabled() && $message->mosque->getNbOfEnabledMessages() >= self::MAX_EANBLED_MESSAGE) {
            $this->context->buildViolation($constraint->messageMaxEnabledReached)->addViolation();

        }
    }
}

