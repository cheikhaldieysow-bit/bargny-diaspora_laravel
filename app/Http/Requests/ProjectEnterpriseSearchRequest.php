<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectEnterpriseSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:1000',
            'status' => 'nullable|array',
            'status.*' => 'string|in:draft,pending,approved,rejected,in_progress,completed',
            'funded' => 'nullable|boolean',
            'min_budget' => 'nullable|numeric|min:0',
            'max_budget' => 'nullable|numeric|min:0',
            'duration_min' => 'nullable|integer|min:0',
            'duration_max' => 'nullable|integer|min:0',
            'created_from' => 'nullable|date',
            'created_to' => 'nullable|date',
            'sort' => 'nullable|string|in:created_at,updated_at,budget,duration,titre',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:200',
            'facets' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('funded')) {
            $this->merge(['funded' => filter_var($this->input('funded'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)]);
        }
        if ($this->has('facets')) {
            $this->merge(['facets' => filter_var($this->input('facets'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)]);
        }
        if ($this->has('status') && ! is_array($this->input('status'))) {
            $this->merge(['status' => array_filter(explode(',', $this->input('status')))]);
        }
    }
}
