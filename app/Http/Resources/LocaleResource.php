<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocaleResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'code'   => $this['code'],
            'native' => $this['native'],
            'url'    => route('locale.set', ['locale' => $this['code']]),
        ];
    }
}
