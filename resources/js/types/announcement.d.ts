export type AnnouncementStatus = 'active' | 'inactive';
export type AnnouncementType =
    | 'event'
    | 'activity'
    | 'news'
    | 'promos';

export type Announcement = {
    id: number;
    title: string;
    body: string;
    type: AnnouncementType;
    image: string | null;
    status: AnnouncementStatus;
    starts_at: string | null;
    expires_at: string | null;
    creator: { id: number; name: string };
};
