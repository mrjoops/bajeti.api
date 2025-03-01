<?php

declare(strict_types=1);

namespace App\Message;

class ListBudgetsMessage implements PaginableInterface, SortableInterface
{
    use PaginableTrait;
    use SortableTrait;
}
