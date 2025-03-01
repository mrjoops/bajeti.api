<?php

declare(strict_types=1);

namespace App\Model\Response;

class ProblemDetailsDto implements \JsonSerializable
{
    public string $detail;
    public string $instance;
    public int $status;
    public string $type = 'about:blank';

    public function __construct(public string $title)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = [
            'title' => $this->title,
            'type' => $this->type,
        ];

        if (isset($this->detail)) {
            $data['detail'] = $this->detail;
        }

        if (isset($this->instance)) {
            $data['instance'] = $this->instance;
        }

        if (isset($this->status)) {
            $data['status'] = $this->status;
        }

        return $data;
    }
}
