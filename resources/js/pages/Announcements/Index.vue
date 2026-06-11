<script setup lang="ts">
import { Head, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { Plus, Globe, Calendar, Zap, Newspaper, BellRing } from 'lucide-vue-next';
import DataTable from '@/components/DataTable.vue';
import Filterable from '@/components/Filterable.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { useInitials } from '@/composables/useInitials';
import { index } from '@/routes/announcements';
import type { Announcement } from '@/types';
import CreateModal from './partials/CreateModal.vue';
import DeleteModal from './partials/DeleteModal.vue';
import EditModal from './partials/EditModal.vue';

type PaginatedAnnouncements = {
    data: Announcement[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{
    announcements: PaginatedAnnouncements;
    filters?: { search?: string; type?: string; status?: string };
}>();

const { getInitials } = useInitials();
const authUserId = computed(() => usePage().props.auth.user?.id);

function isOwner(row: Record<string, any>) {
    return authUserId.value === row.creator?.id;
}

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Announcements', href: index().url }],
    },
});

// Modal state
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedAnnouncement = ref<Announcement | null>(null);

function openEdit(row: Record<string, any>) {
    selectedAnnouncement.value = row as Announcement;
    showEditModal.value = true;
}

function openDelete(row: Record<string, any>) {
    selectedAnnouncement.value = row as Announcement;
    showDeleteModal.value = true;
}

// Search & filter state (seeded from server props)
const search = ref(props.filters?.search ?? '');
const activeFilters = ref<Record<string, string>>({
    type: props.filters?.type ?? '',
    status: props.filters?.status ?? '',
});

// Keep state synchronized with server props (crucial for Inertia page transitions & browser navigation)
watch(
    () => props.filters,
    (newFilters) => {
        search.value = newFilters?.search ?? '';
        activeFilters.value = {
            type: newFilters?.type ?? '',
            status: newFilters?.status ?? '',
        };
    },
    { deep: true }
);

function applyFilters() {
    router.get(
        index().url,
        {
            search: search.value || undefined,
            type: activeFilters.value.type || undefined,
            status: activeFilters.value.status || undefined,
        },
        { preserveState: true, preserveScroll: true, replace: true },
    );
}

function handleSearch(val: string) {
    search.value = val;
    applyFilters();
}

function handleFilters(val: Record<string, string>) {
    activeFilters.value = val;
    applyFilters();
}

// Filter groups definition
const filterGroups = [
    {
        key: 'type',
        label: 'Type',
        options: [
            { label: 'General', value: 'general' },
            { label: 'Event', value: 'event' },
            { label: 'Activity', value: 'activity' },
            { label: 'News', value: 'news' },
            { label: 'Alert', value: 'alert' },
        ],
    },
    {
        key: 'status',
        label: 'Status',
        options: [
            { label: 'Active', value: 'active' },
            { label: 'Inactive', value: 'inactive' },
        ],
    },
];

const columns = [
    { key: 'title', label: 'Title', width: '220px' },
    { key: 'creator', label: 'Created By', width: '160px' },
    { key: 'type', label: 'Type', width: '120px' },
    { key: 'schedule', label: 'Schedule', width: '200px' },
    { key: 'status', label: 'Status', width: '100px' },
];

const actions = [
    { label: 'Edit', handler: openEdit, show: isOwner },
    {
        label: 'Delete',
        variant: 'destructive' as const,
        handler: openDelete,
        show: isOwner,
    },
];

function formatDate(date: string | null) {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function formatType(type: string) {
    const labels: Record<string, string> = {
        general: 'General',
        event: 'Event',
        activity: 'Activity',
        news: 'News',
        alert: 'Alert',
    };
    return labels[type] ?? type;
}

// Type icon map — one lucide icon per type
const typeIcons: Record<string, any> = {
    general: Globe,
    event: Calendar,
    activity: Zap,
    news: Newspaper,
    alert: BellRing,
};

// Type color map — solid flat chips, no border (matches reference image)
const typeColors: Record<string, string> = {
    general: 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-400',
    event: 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-400',
    activity: 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-400',
    news: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400',
    alert: 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-400',
};
</script>

<template>
    <Head title="Announcements" />

    <div class="flex flex-col gap-5 p-4">
        <!-- Page header -->
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-foreground">Announcements</h1>
            <p class="mt-0.5 text-sm text-muted-foreground">
                Manage public announcements shown on the welcome page.
            </p>
        </div>

        <!-- Search, Filters & Action toolbar -->
        <Filterable
            :search="search"
            :filters="activeFilters"
            :filter-groups="filterGroups"
            search-placeholder="Search announcements…"
            @update:search="handleSearch"
            @update:filters="handleFilters"
        >
            <template #actions>
                <Button size="sm" @click="showCreateModal = true">
                    <Plus class="h-4 w-4" />
                    Add New
                </Button>
            </template>
        </Filterable>

        <!-- Data Table -->
        <DataTable
            :columns="columns"
            :rows="announcements.data"
            :actions="actions"
            :current-page="announcements.current_page"
            :last-page="announcements.last_page"
            :per-page="announcements.per_page"
            :total="announcements.total"
            :links="announcements.links"
            empty-message="No announcements found."
        >
            <!-- Title + body preview -->
            <template #cell-title="{ row }">
                <div class="w-full overflow-hidden">
                    <div class="truncate font-semibold text-foreground">{{ row.title }}</div>
                    <div class="mt-0.5 truncate text-xs text-muted-foreground">{{ row.body }}</div>
                </div>
            </template>

            <!-- Creator with avatar -->
            <template #cell-creator="{ row }">
                <div class="flex items-center gap-2.5">
                    <Avatar class="h-7 w-7 shrink-0">
                        <AvatarImage
                            v-if="row.creator.avatar"
                            :src="row.creator.avatar"
                            :alt="row.creator.name"
                        />
                        <AvatarFallback class="text-[10px] font-semibold">
                            {{ getInitials(row.creator.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <span class="text-sm font-medium text-foreground">
                        {{ isOwner(row) ? 'You' : row.creator.name }}
                    </span>
                </div>
            </template>

            <!-- Type badge -->
            <template #cell-type="{ row }">
                <span
                    :class="typeColors[row.type] ?? 'bg-muted/60 text-muted-foreground'"
                    class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-xs font-semibold"
                >
                    <component :is="typeIcons[row.type]" class="h-3 w-3 shrink-0" />
                    {{ formatType(row.type) }}
                </span>
            </template>

            <!-- Schedule (starts_at / expires_at) -->
            <template #cell-schedule="{ row }">
                <div class="text-sm">
                    <div class="font-medium text-foreground">{{ formatDate(row.starts_at) }}</div>
                    <div v-if="row.expires_at" class="mt-0.5 text-xs text-muted-foreground">
                        until {{ formatDate(row.expires_at) }}
                    </div>
                </div>
            </template>

            <!-- Status badge -->
            <template #cell-status="{ row }">
                <span
                    :class="
                        row.status === 'active'
                            ? 'text-green-700 dark:text-green-400'
                            : 'text-muted-foreground'
                    "
                    class="inline-flex items-center gap-1.5 text-xs font-medium capitalize"
                >
                    <span
                        :class="row.status === 'active' ? 'bg-green-500 dark:bg-green-400' : 'bg-muted-foreground/50'"
                        class="h-1.5 w-1.5 rounded-full"
                    />
                    {{ row.status }}
                </span>
            </template>
        </DataTable>
    </div>

    <!-- Modals -->
    <CreateModal v-model:open="showCreateModal" />

    <EditModal
        v-if="selectedAnnouncement"
        v-model:open="showEditModal"
        :announcement="selectedAnnouncement"
    />

    <DeleteModal
        v-if="selectedAnnouncement"
        v-model:open="showDeleteModal"
        :announcement="selectedAnnouncement"
    />
</template>
