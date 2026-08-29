<?php

namespace App\Http\Requests\JobApplication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'applicant_name' => ['required', 'string', 'max:255'],
            'applicant_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('job_applications')->where(function ($query) {
                    $jobPosting = $this->route('jobPosting');
                    return $query->where('job_posting_id', is_object($jobPosting) ? $jobPosting->id : $jobPosting);
                }),
            ],
            'applicant_phone' => ['required', 'string', 'size:11', 'regex:/^09\d{9}$/'],
            'residential_address' => ['nullable', 'string', 'max:500'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'years_of_experience' => ['nullable', 'integer', 'min:0'],
            'has_license' => ['sometimes', 'boolean'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'license_expiry' => ['nullable', 'date'],
            'height_cm' => ['nullable', 'integer', 'min:0'],
            'weight_kg' => ['nullable', 'integer', 'min:0'],
            'resume' => ['required', 'file', 'mimes:pdf,docx', 'max:5120'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'references' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'applicant_email.unique' => 'You have already submitted an application for this position.',
            'applicant_phone.required' => 'The phone number is required.',
            'applicant_phone.size' => 'The phone number must be exactly 11 digits.',
            'applicant_phone.regex' => 'The phone number must be 11 digits starting with 09 and contain no spaces.',
        ];
    }
}
