<?php

declare(strict_types=1);

namespace App\Model\Response;

class ExceptionDto extends ProblemDetailsDto
{
    public string $file;
    public int $line;

    /**
     * @var list<array<string,array<mixed>|int|object|string>>
     */
    public array $trace;

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        if (isset($this->file)) {
            $data['file'] = $this->file;
        }

        if (isset($this->line)) {
            $data['line'] = $this->line;
        }

        if (isset($this->trace)) {
            $data['trace'] = $this->trace;
        }

        return $data;
    }
}
