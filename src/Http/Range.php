<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Range
{
    public function __construct(
        public readonly string $unit = 'items',
        public readonly int $offset = 0,
        public readonly ?int $limit = null,
        public readonly ?int $size = null
    ) {
    }

    public static function createFromRangeHeader(string $rangeHeader): self
    {
        if (1 !== preg_match('/(\d+)-(\d+)?/', $rangeHeader, $matches)) {
            throw new BadRequestHttpException('Failed to parse range');
        }

        return new self(
            explode('=', $rangeHeader)[0],
            intval($matches[1]),
            isset($matches[2]) ? intval($matches[2]) - intval($matches[1]) + 1 : null
        );
    }

    /**
     * @return array<string, string>
     */
    public function toAcceptRangesHeader(): array
    {
        return ['Accept-Ranges' => $this->unit];
    }

    /**
     * @return array<string, string>
     */
    public function toContentRangeHeader(): array
    {
        $size = isset($this->size) && $this->size >= 0 ? '' . $this->size : '*';

        if ($this->valid() && isset($this->limit)) {
            return ['Content-Range' => sprintf(
                '%s %u-%u/%s',
                $this->unit,
                $this->offset,
                $this->offset + $this->limit - 1,
                $size
            )];
        }

        if ('*' === $size) {
            return [];
        }

        return ['Content-Range' => sprintf('%s */%s', $this->unit, $size)];
    }

    public function valid(): bool
    {
        if ($this->offset < 0) {
            return false;
        }

        if (isset($this->limit) && $this->limit < 0 || is_null($this->limit)) {
            return false;
        }

        if (isset($this->size) && ($this->size < 0 || $this->offset > $this->size)) {
            return false;
        }

        return true;
    }
}
