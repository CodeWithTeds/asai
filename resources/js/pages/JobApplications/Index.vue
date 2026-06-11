<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DataTable from '@/components/DataTable.vue';
import Filterable from '@/components/Filterable.vue';
import jobApplicationsRoute from '@/routes/job-applications';
import type { JobApplication } from '@/types';

type PaginatedJobApplications = {
    data: JobApplication[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{
    jobApplications: PaginatedJobApplications;
    filters?: { search?: string; has_license?: string };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Applications', href: jobApplicationsRoute.index().url },
        ],
    },
});

// Search & filter state
const search = ref(props.filters?.search ?? '');
const activeFilters = ref<Record<string, string>>({
    has_license: props.filters?.has_license ?? '',
});

// Sync with server props
watch(
    () => props.filters,
    (newFilters) => {
        search.value = newFilters?.search ?? '';
        activeFilters.value = {
            has_license: newFilters?.has_license ?? '',
        };
    },
    { deep: true },
);

function applyFilters() {
    router.get(
        jobApplicationsRoute.index().url,
        {
            search: search.value || undefined,
            has_license: activeFilters.value.has_license || undefined,
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

// Filter groups
const filterGroups = [
    {
        key: 'has_license',
        label: 'License',
        options: [
            { label: 'Licensed', value: '1' },
            { label: 'No License', value: '0' },
        ],
    },
];

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

const columns = [
    { key: 'applicant_name', label: 'Applicant', width: '220px' },
    { key: 'job_posting', label: 'Applied For', width: '180px' },
    { key: 'years_of_experience', label: 'Experience', width: '100px' },
    { key: 'has_license', label: 'License', width: '110px' },
    { key: 'created_at', label: 'Date Applied', width: '180px' },
];

const actions = [
    {
        label: 'View',
        handler: (row: Record<string, any>) =>
            router.visit(jobApplicationsRoute.show(row.id).url),
    },
];
</script>

<template>
    <Head title="Job Applications" />

    <div class="flex flex-col gap-5 p-4">
        <!-- Page header -->
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-foreground">Job Applications</h1>
            <p class="mt-0.5 text-sm text-muted-foreground">
                Review and manage applications submitted by job candidates.
            </p>
        </div>

        <!-- Search, Filters -->
        <Filterable
            :search="search"
            :filters="activeFilters"
            :filter-groups="filterGroups"
            search-placeholder="Search by name or email…"
            @update:search="handleSearch"
            @update:filters="handleFilters"
        />

        <!-- Data Table -->
        <DataTable
            :columns="columns"
            :rows="jobApplications.data"
            :actions="actions"
            :current-page="jobApplications.current_page"
            :last-page="jobApplications.last_page"
            :per-page="jobApplications.per_page"
            :total="jobApplications.total"
            :links="jobApplications.links"
            empty-message="No job applications found."
        >
            <!-- Applicant details cell -->
            <template #cell-applicant_name="{ row }">
                <div class="w-full overflow-hidden">
                    <div class="truncate font-semibold text-foreground">
                        {{ row.applicant_name }}
                    </div>
                    <div class="mt-0.5 truncate text-xs text-muted-foreground">
                        {{ row.applicant_email }}
                        <span v-if="row.applicant_phone">· {{ row.applicant_phone }}</span>
                    </div>
                </div>
            </template>

            <!-- Job Posting cell -->
            <template #cell-job_posting="{ row }">
                <span class="text-sm font-medium text-foreground">
                    {{ row.job_posting?.title ?? '—' }}
                </span>
            </template>

            <!-- Experience cell -->
            <template #cell-years_of_experience="{ row }">
                <span class="text-sm text-foreground">
                    {{ row.years_of_experience }}
                    {{ row.years_of_experience === 1 ? 'yr' : 'yrs' }}
                </span>
            </template>

            <!-- License cell -->
            <template #cell-has_license="{ row }">
                <span
                    :class="
                        row.has_license
                            ? 'text-green-700 dark:text-green-400'
                            : 'text-muted-foreground'
                    "
                    class="inline-flex items-center gap-1.5 text-xs font-medium"
                >
                    <span
                        :class="row.has_license ? 'bg-green-500 dark:bg-green-400' : 'bg-muted-foreground/50'"
                        class="h-1.5 w-1.5 rounded-full"
                    />
                    {{ row.has_license ? 'Licensed' : 'No License' }}
                </span>
            </template>

            <!-- Date Applied cell -->
            <template #cell-created_at="{ row }">
                <span class="text-sm text-muted-foreground">
                    {{ formatDate(row.created_at) }}
                </span>
            </template>
        </DataTable>
    </div>
</template>
