<?php

namespace App\Http\Requests\JobPosting;

use App\Enums\JobPostingStatus;
use App\Enums\JobPostingType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreJobPostingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'location'    => ['nullable', 'string', 'max:255'],
            'type'        => ['required', new Enum(JobPostingType::class)],
            'status'      => ['sometimes', new Enum(JobPostingStatus::class)],
            'starts_at'   => ['nullable', 'date'],
            'expires_at'  => ['nullable', 'date', 'after:starts_at'],
        ];
    }
}
