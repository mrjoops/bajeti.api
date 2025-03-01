<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Stringable;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'categories')]
class Category implements IdInterface, Stringable, Translatable
{
    use IdTrait;

    #[Gedmo\Locale]
    public string $locale;

    public function __construct(
        #[Gedmo\Translatable]
        #[ORM\Column(length: 255)]
        public readonly string $name,
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
