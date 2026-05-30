export type AnnouncementStatus = 'active' | 'inactive';

export type Announcement = {
    id: number;
    title: string;
    body: string;
    status: AnnouncementStatus;
    starts_at: string | null;
    expires_at: string | null;
    creator: { id: number; name: string };
};
