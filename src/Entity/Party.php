<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaxonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxonRepository::class)]
class Party extends Taxon
{
}
