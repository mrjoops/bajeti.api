<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'accounts')]
class Account implements IdInterface, Stringable
{
    use IdTrait;

    /**
     * @var Collection<int, Operation>
     */
    #[ORM\OneToMany(targetEntity: Operation::class, mappedBy: 'account')]
    public readonly Collection $operations;

    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'accounts')]
        #[ORM\JoinColumn(nullable: false)]
        public Budget $budget,
        #[ORM\Column(length: 255)]
        public readonly string $name,
    ) {
        $this->operations = new ArrayCollection();
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }

    public function addOperation(Operation $operation): void
    {
        if (!$this->operations->contains($operation)) {
            $this->operations->add($operation);
            $operation->account = $this;
        }
    }

    public function removeOperation(Operation $operation): void
    {
        $this->operations->removeElement($operation);
    }
}
