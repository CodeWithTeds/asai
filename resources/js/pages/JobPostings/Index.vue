<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { useInitials } from '@/composables/useInitials';
import { index } from '@/routes/job-postings';
import type { JobPosting } from '@/types';
import CreateModal from './partials/CreateModal.vue';
import DeleteModal from './partials/DeleteModal.vue';
import EditModal from './partials/EditModal.vue';

type PaginatedJobPostings = {
    data: JobPosting[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

defineProps<{ jobPostings: PaginatedJobPostings }>();

const { getInitials } = useInitials();
const authUserId = computed(() => usePage().props.auth.user?.id);

function isOwner(row: Record<string, any>) {
    return authUserId.value === row.creator?.id;
}

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Job Postings', href: index().url }],
    },
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedJobPosting = ref<JobPosting | null>(null);

function openEdit(row: Record<string, any>) {
    selectedJobPosting.value = row as JobPosting;
    showEditModal.value = true;
}

function openDelete(row: Record<string, any>) {
    selectedJobPosting.value = row as JobPosting;
    showDeleteModal.value = true;
}

function formatDate(date: string | null) {
    if (!date) {
        return '—';
    }

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
        full_time: 'Full Time',
        part_time: 'Part Time',
        contract: 'Contract',
    };

    return labels[type] ?? type;
}

const columns = [
    { key: 'title', label: 'Title' },
    { key: 'creator', label: 'Created By' },
    { key: 'type', label: 'Type' },
    { key: 'schedule', label: 'Schedule' },
    { key: 'status', label: 'Status' },
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
</script>

<template>
    <Head title="Job Postings" />

    <div class="flex flex-col gap-6 p-4">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Job Postings</h1>
                <p class="text-sm text-muted-foreground">
                    Manage job postings shown on the welcome page.
                </p>
            </div>
            <Button @click="showCreateModal = true">New Job Posting</Button>
        </div>

        <!-- Data Table -->
        <DataTable
            :columns="columns"
            :rows="jobPostings.data"
            :actions="actions"
            :current-page="jobPostings.current_page"
            :last-page="jobPostings.last_page"
            :per-page="jobPostings.per_page"
            :total="jobPostings.total"
            :links="jobPostings.links"
            empty-message="No job postings yet."
        >
            <!-- Title + description preview -->
            <template #cell-title="{ row }">
                <div>
                    <div class="font-semibold text-foreground">
                        {{ row.title }}
                    </div>
                    <div
                        class="mt-0.5 max-w-[220px] truncate text-xs text-muted-foreground"
                    >
                        {{ row.description }}
                    </div>
                </div>
            </template>

            <!-- Creator with avatar -->
            <template #cell-creator="{ row }">
                <div class="flex items-center gap-2.5">
                    <Avatar class="h-8 w-8">
                        <AvatarImage
                            v-if="row.creator.avatar"
                            :src="row.creator.avatar"
                            :alt="row.creator.name"
                        />
                        <AvatarFallback class="text-[10px] font-semibold">
                            {{ getInitials(row.creator.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <span class="font-medium">{{
                        isOwner(row) ? 'You' : row.creator.name
                    }}</span>
                </div>
            </template>

            <!-- Type badge -->
            <template #cell-type="{ row }">
                <span
                    class="inline-flex items-center rounded-full border border-border bg-muted px-2.5 py-0.5 text-xs font-medium text-foreground"
                >
                    {{ formatType(row.type) }}
                </span>
            </template>

            <!-- Schedule (starts_at / expires_at combined) -->
            <template #cell-schedule="{ row }">
                <div>
                    <div class="font-medium text-foreground">
                        {{ formatDate(row.starts_at) }}
                    </div>
                    <div
                        v-if="row.expires_at"
                        class="mt-0.5 text-xs text-muted-foreground"
                    >
                        until {{ formatDate(row.expires_at) }}
                    </div>
                </div>
            </template>

            <!-- Status badge -->
            <template #cell-status="{ row }">
                <span
                    :class="
                        row.status === 'open'
                            ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950 dark:text-green-400'
                            : 'border-border bg-muted text-muted-foreground'
                    "
                    class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium capitalize"
                >
                    {{ row.status }}
                </span>
            </template>
        </DataTable>
    </div>

    <!-- Modals -->
    <CreateModal v-model:open="showCreateModal" />

    <EditModal
        v-if="selectedJobPosting"
        v-model:open="showEditModal"
        :job-posting="selectedJobPosting"
    />

    <DeleteModal
        v-if="selectedJobPosting"
        v-model:open="showDeleteModal"
        :job-posting="selectedJobPosting"
    />
</template>
