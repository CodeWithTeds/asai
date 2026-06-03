export type JobApplication = {
    id: number;
    applicant_name: string;
    applicant_email: string;
    applicant_phone: string | null;
    residential_address: string | null;
    education_level: string | null;
    years_of_experience: number;
    has_license: boolean;
    license_number: string | null;
    license_expiry: string | null;
    height_cm: number | null;
    weight_kg: number | null;
    resume_path: string;
    cover_letter: string | null;
    references: string | null;
    created_at: string;
    job_posting: {
        id: number;
        title: string;
    };
};
