<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'per_page'       => $this->perPage(),
            'total'          => $this->total(),
            'current_page'   => $this->currentPage(),
            'last_page'      => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages(),
            'has_pages'      => $this->hasPages(),

            'first_page_url' => $this->url(1),
            'last_page_url'  => $this->url($this->lastPage()),

            'previous_page_url' => $this->previousPageUrl(),
            'next_page_url'     => $this->nextPageUrl(),

            'page_urls' => $this->getUrlRange(1, $this->lastPage()),
        ];
    }
}
