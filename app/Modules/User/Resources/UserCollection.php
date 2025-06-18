<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = UserResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'users' => $this->collection,
            'statistics' => [
                'total_users' => $this->collection->count(),
                'active_users' => $this->collection->where('is_active', true)->count(),
                'inactive_users' => $this->collection->where('is_active', false)->count(),
                'verified_users' => $this->collection->whereNotNull('email_verified_at')->count(),
                'unverified_users' => $this->collection->whereNull('email_verified_at')->count(),
            ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'resource_type' => 'user_collection',
                'timestamp' => now()->toDateTimeString(),
                'total_count' => $this->collection->count(),
                'page_info' => [
                    'current_page' => $this->resource->currentPage() ?? 1,
                    'per_page' => $this->resource->perPage() ?? $this->collection->count(),
                    'total' => $this->resource->total() ?? $this->collection->count(),
                    'last_page' => $this->resource->lastPage() ?? 1,
                ],
            ],
        ];
    }

    /**
     * Customize the pagination information for the resource.
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            'pagination' => [
                'current_page' => $default['meta']['current_page'],
                'from' => $default['meta']['from'],
                'to' => $default['meta']['to'],
                'per_page' => $default['meta']['per_page'],
                'total' => $default['meta']['total'],
                'last_page' => $default['meta']['last_page'],
            ],
            'links' => $default['links'],
        ];
    }
}
