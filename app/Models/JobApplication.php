<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'job_posting_id',
    'applicant_name',
    'applicant_email',
    'applicant_phone',
    'residential_address',
    'education_level',
    'years_of_experience',
    'has_license',
    'license_number',
    'license_expiry',
    'height_cm',
    'weight_kg',
    'resume_path',
    'cover_letter',
    'references'
])]
class JobApplication extends Model
{
    protected function casts(): array
    {
        return [
            'has_license' => 'boolean',
            'license_expiry' => 'date',
        ];
    }

    /**
     * The job posting this application is for.
     */
    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }
}
