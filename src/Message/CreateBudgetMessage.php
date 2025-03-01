<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Validator\Constraints;

class CreateBudgetMessage
{
    public \DateTimeImmutable $dateEnd;
    public \DateTimeImmutable $dateStart;

    #[Constraints\NotBlank]
    public string $name;
}
