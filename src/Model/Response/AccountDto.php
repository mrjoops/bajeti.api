<?php

declare(strict_types=1);

namespace App\Model\Response;

use Symfony\Component\Uid\Uuid;

class AccountDto extends HalDto
{
    public function __construct(
        private Uuid $budgetId,
        private Uuid $id,
        private bool $isPersonal,
        private string $name,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $array = [
            'name' => $this->name,
            'personal' => $this->isPersonal,
        ];
        $array = $this->addLink($array, 'budget', '/budgets/' . $this->budgetId);
        $array = $this->addLink($array, 'operations', '/accounts/' . $this->id . '/operations');
        $array = $this->addLink($array, 'self', '/accounts/' . $this->id);

        return $array;
    }
}
