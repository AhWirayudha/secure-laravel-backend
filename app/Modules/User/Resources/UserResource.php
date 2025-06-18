<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->toDateTimeString(),
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->toDateTimeString(),
            'last_login_ip' => $this->when($request->user()->can('view-sensitive-data'), $this->last_login_ip),
            'login_count' => $this->login_count,
            'two_factor_enabled' => $this->two_factor_enabled,
            'roles' => $this->getRoleNames(),
            'permissions' => $this->when(
                $request->user()->can('view-permissions'),
                $this->getAllPermissions()->pluck('name')
            ),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->when($this->trashed(), $this->deleted_at?->toDateTimeString()),
            
            // Additional computed fields
            'initials' => $this->initials,
            'full_name' => $this->full_name,
            'status' => $this->is_active ? 'active' : 'inactive',
            'account_age_days' => $this->created_at->diffInDays(now()),
            'last_login_human' => $this->last_login_at?->diffForHumans(),
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
                'resource_type' => 'user',
                'timestamp' => now()->toDateTimeString(),
            ],
        ];
    }
}
