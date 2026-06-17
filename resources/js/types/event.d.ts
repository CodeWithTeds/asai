export type EventStatus = 'active' | 'inactive';
export type EventType = 'event' | 'activity' | 'news' | 'promos';

export type Event = {
    id: number;
    title: string;
    body: string;
    type: EventType;
    image: string | null;
    status: EventStatus;
    starts_at: string | null;
    expires_at: string | null;
    creator: { id: number; name: string };
};
