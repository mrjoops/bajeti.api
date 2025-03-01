<?php

declare(strict_types=1);

namespace App\Model\Response;

use Symfony\Component\Uid\Uuid;

class OperationDto extends HalDto
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        private Uuid $accountId,
        private string $amount,
        private string $category,
        private \DateTimeInterface $date,
        private Uuid $id,
        private string $name,
        private string $party,
        private array $tags,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $array = [
            'amount' => $this->amount,
            'category' => $this->category,
            'date' => $this->date->format('c'),
            'name' => $this->name,
            'party' => $this->party,
            'tags' => $this->tags,
        ];
        $array = $this->addLink($array, 'account', '/accounts/' . $this->accountId);
        $array = $this->addLink($array, 'self', '/operations/' . $this->id);

        return $array;
    }
}
