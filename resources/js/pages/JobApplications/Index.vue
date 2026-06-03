<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import DataTable from '@/components/DataTable.vue';
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

defineProps<{ jobApplications: PaginatedJobApplications }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Applications', href: jobApplicationsRoute.index().url },
        ],
    },
});

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
    { key: 'applicant_name', label: 'Applicant' },
    { key: 'job_posting', label: 'Applied For' },
    { key: 'years_of_experience', label: 'Experience' },
    { key: 'has_license', label: 'License' },
    { key: 'created_at', label: 'Date Applied' },
];

const actions = [
    {
        label: 'Show',
        handler: (row: Record<string, any>) =>
            router.visit(jobApplicationsRoute.show(row.id).url),
    },
];
</script>

<template>
    <Head title="Job Applications" />

    <div class="flex flex-col gap-6 p-4">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Job Applications</h1>
                <p class="text-sm text-muted-foreground">
                    Review and manage applications submitted by job candidates.
                </p>
            </div>
        </div>

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
            empty-message="No job applications yet."
        >
            <!-- Applicant details cell -->
            <template #cell-applicant_name="{ row }">
                <div>
                    <div class="font-semibold text-foreground">
                        {{ row.applicant_name }}
                    </div>
                    <div class="mt-0.5 text-xs text-muted-foreground">
                        {{ row.applicant_email }}
                        <span v-if="row.applicant_phone"
                            >· {{ row.applicant_phone }}</span
                        >
                    </div>
                </div>
            </template>

            <!-- Job Posting cell -->
            <template #cell-job_posting="{ row }">
                <span class="font-medium text-foreground">
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
                    v-if="row.has_license"
                    class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-emerald-600/20 ring-inset dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20"
                >
                    Licensed
                </span>
                <span
                    v-else
                    class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-yellow-600/10 ring-inset dark:bg-yellow-400/10 dark:text-yellow-500 dark:ring-yellow-400/20"
                >
                    No License
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
