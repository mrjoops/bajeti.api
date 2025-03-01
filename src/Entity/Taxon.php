<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaxonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\DiscriminatorColumn(name: 'discr', type: Types::SMALLINT)]
#[ORM\DiscriminatorMap([1 => Party::class, 2 => Tag::class])]
#[ORM\Entity(repositoryClass: TaxonRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\Table(name: 'taxons')]
class Taxon implements IdInterface, Stringable
{
    use IdTrait;

    public function __construct(
        #[ORM\Column(length: 255, unique: true)]
        public readonly string $name,
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
