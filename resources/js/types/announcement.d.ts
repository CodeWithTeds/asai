export type Announcement = {
    id: number;
    title: string;
    body: string;
    is_active: boolean;
    starts_at: string | null;
    expires_at: string | null;
    creator: { id: number; name: string };
};
