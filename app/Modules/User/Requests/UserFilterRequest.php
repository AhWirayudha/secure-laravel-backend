<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('view-users');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255',
            'sort_by' => 'sometimes|string|in:name,email,created_at,updated_at,last_login_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'status' => 'sometimes|string|in:active,inactive,all',
            'role' => 'sometimes|string|exists:roles,name',
            'verified' => 'sometimes|boolean',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'per_page.max' => 'You can only retrieve up to 100 users per page.',
            'sort_by.in' => 'You can only sort by: name, email, created_at, updated_at, or last_login_at.',
            'sort_order.in' => 'Sort order must be either asc or desc.',
            'status.in' => 'Status must be either active, inactive, or all.',
            'role.exists' => 'The selected role does not exist.',
            'date_to.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'page' => $this->page ?? 1,
            'per_page' => $this->per_page ?? 15,
            'sort_by' => $this->sort_by ?? 'created_at',
            'sort_order' => $this->sort_order ?? 'desc',
            'status' => $this->status ?? 'all',
        ]);

        if ($this->has('search')) {
            $this->merge(['search' => trim($this->search)]);
        }
    }
}
