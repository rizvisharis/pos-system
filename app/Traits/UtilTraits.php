<?php

namespace App\Traits;

use App\Utils\Constants;
use Illuminate\Support\Str;

trait UtilTraits
{
    public function getPaginateInfo($results)
    {
        return [
            'count' => $results->count(),
            'current_page' => $results->currentPage(),
            'first_item' => $results->firstItem(),
            'has_more_pages' => $results->hasMorePages(),
            'last_item' => $results->lastItem(),
            'last_page' => $results->lastPage(),
            'per_page' => $results->perPage(),
            'total' => $results->total(),
            'next_page_url' => $results->withQueryString()->nextPageUrl(),
            'next_page_param' => Str::after($results->withQueryString()->nextPageUrl(), Constants::$PAGINATION_PAGE_PARAM),
            'previous_page_url' => $results->withQueryString()->previousPageUrl(),
            'previous_page_param' => Str::after($results->withQueryString()->previousPageUrl(), Constants::$PAGINATION_PAGE_PARAM),
            'first_page_url' => $results->withQueryString()->url(1),
            'first_page_param' => Str::after($results->withQueryString()->url(1), Constants::$PAGINATION_PAGE_PARAM),
            'last_page_url' => $results->withQueryString()->url($results->lastPage()),
            'last_page_param' => Str::after($results->withQueryString()->url($results->lastPage()), Constants::$PAGINATION_PAGE_PARAM),
            'path' => $results->path(),
        ];
    }
}
