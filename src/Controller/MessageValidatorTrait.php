<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait MessageValidatorTrait
{
    private ValidatorInterface $validator;

    public function validateMessage(object $message): void
    {
        $violations = $this->validator->validate($message);

        if (count($violations) > 0) {
            throw new BadRequestHttpException('' . $violations);
        }
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}
