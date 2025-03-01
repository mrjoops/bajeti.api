<?php

declare(strict_types=1);

namespace App\Model\Response;

use App\Model\Response\BudgetDto;

class BudgetListDto extends HalDto
{
    /**
     * @param array<BudgetDto> $budgets
     */
    public function __construct(private array $budgets)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $array = $this->addLink([], 'self', '/api/budgets');

        foreach ($this->budgets as $budget) {
            $array = $this->addResource($array, 'budgets', $budget);
        }

        return $array;
    }
}
