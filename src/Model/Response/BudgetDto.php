<?php

declare(strict_types=1);

namespace App\Model\Response;

use App\Model\Response\AccountDto;
use Symfony\Component\Uid\Uuid;

class BudgetDto extends HalDto
{
    /**
     * @param array<AccountDto> $accounts
     */
    public function __construct(
        private array $accounts,
        private \DateTimeInterface $dateEnd,
        private \DateTimeInterface $dateStart,
        private Uuid $id,
        private string $name,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $array = [
            'dateEnd' => $this->dateEnd->format('Y-m-d'),
            'dateStart' => $this->dateStart->format('Y-m-d'),
            'name' => $this->name,
        ];
        $array = $this->addLink($array, 'self', '/budgets/' . $this->id);
        array_walk($this->accounts, fn (AccountDto $account) => $this->addResource($array, 'accounts', $account));

        return $array;
    }
}
