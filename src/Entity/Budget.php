<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BudgetRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'budgets')]
class Budget implements IdInterface, Stringable
{
    use IdTrait;

    /**
     * @var Collection<int, Account>
     */
    #[ORM\OneToMany(targetEntity: Account::class, mappedBy: 'budget')]
    public readonly Collection $accounts;

    public function __construct(
        #[ORM\Column(type: Types::DATE_IMMUTABLE)]
        public readonly DateTimeImmutable $endsAt,
        #[ORM\Column(length: 255)]
        public readonly string $name,
        #[ORM\Column(type: Types::DATE_IMMUTABLE)]
        public readonly DateTimeImmutable $startsAt,
    ) {
        $this->accounts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function addAccount(Account $account): void
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->budget = $this;
        }
    }

    public function removeAccount(Account $account): void
    {
        $this->accounts->removeElement($account);
    }
}
