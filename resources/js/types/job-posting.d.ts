export type JobPostingStatus = 'open' | 'closed';

export type JobPostingType = 'full_time' | 'part_time' | 'contract';

export type JobPosting = {
    id: number;
    title: string;
    description: string;
    cover_image: string | null;
    location: string | null;
    type: JobPostingType;
    status: JobPostingStatus;
    starts_at: string | null;
    expires_at: string | null;
    creator: { id: number; name: string };
};
