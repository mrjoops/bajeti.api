<?php

declare(strict_types=1);

namespace App\Model\Response\Assembler;

use App\Entity\Operation;
use App\Entity\Tag;
use App\Model\Response\OperationDto;
use InvalidArgumentException;

class OperationDtoAssembler
{
    public function create(Operation $operation): OperationDto
    {
        if (is_null($operation->account->uuid) || is_null($operation->uuid)) {
            throw new InvalidArgumentException();
        }

        return new OperationDto(
            $operation->account->uuid,
            $operation->amount,
            $operation->category->name ?? '',
            $operation->date,
            $operation->uuid,
            $operation->name,
            $operation->party->name ?? '',
            $operation->tags->map(function (Tag $tag) {
                return $tag->name;
            })->toArray(),
        );
    }
}
