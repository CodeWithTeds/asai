<?php

namespace App\Http\Requests\JobPosting;

use App\Enums\JobPostingStatus;
use App\Enums\JobPostingType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateJobPostingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\JobPosting $jobPosting */
        $jobPosting = $this->route('job_posting');

        return $this->user()?->can('update', $jobPosting) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'               => ['required', 'string', 'max:255'],
            'description'         => ['required', 'string', 'max:5000'],
            'cover_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_cover_image'  => ['sometimes', 'boolean'],
            'location'            => ['nullable', 'string', 'max:255'],
            'type'                => ['required', new Enum(JobPostingType::class)],
            'status'              => ['sometimes', new Enum(JobPostingStatus::class)],
            'starts_at'           => ['nullable', 'date'],
            'expires_at'          => ['nullable', 'date', 'after:starts_at'],
        ];
    }
}
