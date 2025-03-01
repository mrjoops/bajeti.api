<?php

namespace App\Controller;

interface MessageValidatorInterface
{
    public function validateMessage(object $message): void;
}
