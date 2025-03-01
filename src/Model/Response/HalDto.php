<?php

declare(strict_types=1);

namespace App\Model\Response;

abstract class HalDto implements \JsonSerializable
{
    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function addLink(array $input, string $rel, string $href): array
    {
        $links = [
            '_links' => [
                $rel => [
                    'href' => $href
                ]
            ]
        ];

        return array_merge_recursive($input, $links);
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function addResource(array $input, string $rel, HalDto $resource): array
    {
        if (!array_key_exists('_embedded', $input) || !is_array($input['_embedded'])) {
            $input['_embedded'] = [];
        }

        if (!array_key_exists($rel, $input['_embedded'])) {
            $input['_embedded'][$rel] = $resource->jsonSerialize();

            return $input;
        }

        if (!is_array($input['_embedded'][$rel])) {
            $input['_embedded'][$rel] = [];
        }

        if (!array_key_exists(0, $input['_embedded'][$rel])) {
            $input['_embedded'][$rel] = [$input['_embedded'][$rel]];
        }

        $input['_embedded'][$rel][] = $resource->jsonSerialize();

        return $input;
    }
}
