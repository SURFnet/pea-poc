<?php

declare(strict_types=1);

namespace App\Dto;

use JsonSerializable;

class PiwikDataLayerDto implements JsonSerializable
{
    public function __construct(private readonly string $instituteShortName, private readonly ?array $roles)
    {
    }

    /**
     * @return array{spvainstelling: string, spvarol: string[]}
     */
    public function jsonSerialize(): array
    {
        return [
            'spvainstelling' => $this->instituteShortName,
            'spvarol'        => $this->roles ?? [],
        ];
    }
}
