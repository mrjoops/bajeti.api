<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    public ?Uuid $uuid = null;

    #[ORM\PrePersist]
    public function generateUuid(): void
    {
        $this->uuid = Uuid::v4();
    }
}
