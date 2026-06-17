<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Megaphone,
    Briefcase,
    FileText,
    ChevronRight,
    User,
    Calendar,
} from 'lucide-vue-next';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index as eventsIndex } from '@/routes/events/manage';
import { index as jobApplicationsIndex } from '@/routes/job-applications';
import { index as jobPostingsIndex } from '@/routes/job-postings';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

defineProps<{
    stats: {
        events: {
            total: number;
            active: number;
        };
        job_postings: {
            total: number;
            open: number;
        };
        job_applications: {
            total: number;
        };
    };
    recent_events: Array<{
        id: number;
        title: string;
        type: string;
        status: string;
        creator_name: string;
        created_at: string;
    }>;
    recent_job_postings: Array<{
        id: number;
        title: string;
        type: string;
        status: string;
        creator_name: string;
        created_at: string;
    }>;
    recent_job_applications: Array<{
        id: number;
        applicant_name: string;
        applicant_email: string;
        job_title: string;
        created_at: string;
    }>;
}>();

function formatDate(dateString: string) {
    return new Date(dateString).toLocaleDateString('en-PH', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function getInitials(name: string) {
    return name
        .split(' ')
        .map((n) => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
}

const typeColors: Record<string, string> = {
    event: 'bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-400 border border-violet-200/40 dark:border-violet-800/30',
    activity:
        'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/40 dark:border-amber-800/30',
    news: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/40 dark:border-emerald-800/30',
    promos: 'bg-pink-50 text-pink-700 dark:bg-pink-950/40 dark:text-pink-400 border border-pink-200/40 dark:border-pink-800/30',
};

const jobTypeColors: Record<string, string> = {
    'full-time':
        'bg-teal-50 text-teal-700 dark:bg-teal-950/40 dark:text-teal-400 border border-teal-200/40 dark:border-teal-800/30',
    'part-time':
        'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400 border border-orange-200/40 dark:border-orange-800/30',
    contract:
        'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 border border-indigo-200/40 dark:border-indigo-800/30',
    internship:
        'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200/40 dark:border-blue-800/30',
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-6 p-6">
        <!-- Dashboard header -->
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">
                Dashboard
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Overview of recruitment metrics, active postings, and recent
                updates.
            </p>
        </div>

        <!-- Metric Cards -->
        <div class="grid gap-5 md:grid-cols-3">
            <!-- Applications Card -->
            <div
                class="group relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-card p-6 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-sidebar-border/40"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-muted-foreground"
                        >Job Applications</span
                    >
                    <div
                        class="rounded-lg bg-blue-50 p-2.5 text-blue-600 transition-colors duration-200 group-hover:bg-blue-100 dark:bg-blue-950/50 dark:text-blue-400"
                    >
                        <FileText class="h-5 w-5" />
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-foreground">
                        {{ stats.job_applications.total }}
                    </h3>
                    <p class="mt-1.5 text-xs text-muted-foreground">
                        Total applications received
                    </p>
                </div>
            </div>

            <!-- Job Postings Card -->
            <div
                class="group relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-card p-6 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-sidebar-border/40"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-muted-foreground"
                        >Active Job Postings</span
                    >
                    <div
                        class="rounded-lg bg-teal-50 p-2.5 text-teal-600 transition-colors duration-200 group-hover:bg-teal-100 dark:bg-teal-950/50 dark:text-teal-400"
                    >
                        <Briefcase class="h-5 w-5" />
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-foreground">
                        {{ stats.job_postings.open }}
                    </h3>
                    <p class="mt-1.5 text-xs text-muted-foreground">
                        {{ stats.job_postings.total }} total positions created
                    </p>
                </div>
            </div>

            <!-- Events Card -->
            <div
                class="group relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-card p-6 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-sidebar-border/40"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-muted-foreground"
                        >Active Events</span
                    >
                    <div
                        class="rounded-lg bg-amber-50 p-2.5 text-amber-600 transition-colors duration-200 group-hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-400"
                    >
                        <Megaphone class="h-5 w-5" />
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-foreground">
                        {{ stats.events.active }}
                    </h3>
                    <p class="mt-1.5 text-xs text-muted-foreground">
                        {{ stats.events.total }} total events posted
                    </p>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid gap-6 lg:grid-cols-12">
            <!-- Left Column: Recent Applications -->
            <div
                class="rounded-xl border border-sidebar-border/70 bg-card p-5 lg:col-span-7 xl:col-span-8 dark:border-sidebar-border/40"
            >
                <div
                    class="flex items-center justify-between border-b border-sidebar-border/70 pb-4 dark:border-sidebar-border/40"
                >
                    <div>
                        <h2 class="text-lg font-semibold text-foreground">
                            Recent Job Applications
                        </h2>
                        <p class="text-xs text-muted-foreground">
                            Latest candidates seeking open positions.
                        </p>
                    </div>
                    <Button
                        variant="ghost"
                        size="sm"
                        as-child
                        class="h-8 text-xs font-semibold"
                    >
                        <Link :href="jobApplicationsIndex()">
                            View All
                            <ChevronRight class="ml-1 h-3.5 w-3.5" />
                        </Link>
                    </Button>
                </div>

                <div
                    class="mt-4 divide-y divide-sidebar-border/70 dark:divide-sidebar-border/40"
                >
                    <div
                        v-if="recent_job_applications.length === 0"
                        class="flex flex-col items-center justify-center py-10 text-center"
                    >
                        <User class="h-10 w-10 text-muted-foreground/50" />
                        <h3 class="mt-2 text-sm font-medium text-foreground">
                            No applications
                        </h3>
                        <p class="mt-1 text-xs text-muted-foreground">
                            New candidates will show up here.
                        </p>
                    </div>

                    <div
                        v-for="app in recent_job_applications"
                        :key="app.id"
                        class="flex items-center justify-between py-4 first:pt-0 last:pb-0"
                    >
                        <div class="flex items-center gap-3.5">
                            <Avatar class="h-9 w-9 shrink-0">
                                <AvatarFallback
                                    class="bg-neutral-100 font-semibold text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200"
                                >
                                    {{ getInitials(app.applicant_name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="min-w-0">
                                <h4
                                    class="truncate text-sm font-semibold text-foreground"
                                >
                                    {{ app.applicant_name }}
                                </h4>
                                <p
                                    class="truncate text-xs text-muted-foreground"
                                >
                                    {{ app.applicant_email }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1.5 text-right">
                            <span
                                class="inline-flex rounded-md bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-800 dark:bg-neutral-800 dark:text-neutral-300"
                            >
                                {{ app.job_title }}
                            </span>
                            <span
                                class="flex items-center gap-1 text-[10px] text-muted-foreground"
                            >
                                <Calendar class="h-3 w-3" />
                                {{ formatDate(app.created_at) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Events & Job Postings -->
            <div class="flex flex-col gap-6 lg:col-span-5 xl:col-span-4">
                <!-- Recent Job Postings -->
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border/40"
                >
                    <div
                        class="flex items-center justify-between border-b border-sidebar-border/70 pb-4 dark:border-sidebar-border/40"
                    >
                        <div>
                            <h2 class="text-md font-semibold text-foreground">
                                Recent Job Postings
                            </h2>
                            <p class="text-[11px] text-muted-foreground">
                                Newly opened employment opportunities.
                            </p>
                        </div>
                        <Button
                            variant="ghost"
                            size="sm"
                            as-child
                            class="h-8 text-xs font-semibold"
                        >
                            <Link :href="jobPostingsIndex()">
                                Manage
                                <ChevronRight class="ml-1 h-3.5 w-3.5" />
                            </Link>
                        </Button>
                    </div>

                    <div class="mt-4 space-y-3.5">
                        <div
                            v-if="recent_job_postings.length === 0"
                            class="py-6 text-center text-xs text-muted-foreground"
                        >
                            No job postings listed yet.
                        </div>

                        <div
                            v-for="job in recent_job_postings"
                            :key="job.id"
                            class="flex flex-col gap-1.5 rounded-lg border border-sidebar-border/40 bg-neutral-50/40 p-3 transition-colors duration-150 hover:bg-neutral-50 dark:border-sidebar-border/20 dark:bg-neutral-900/10 dark:hover:bg-neutral-900/30"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <h4
                                    class="line-clamp-1 text-sm font-semibold text-foreground"
                                >
                                    {{ job.title }}
                                </h4>
                                <span
                                    :class="
                                        jobTypeColors[job.type] ??
                                        'bg-muted text-[10px] text-muted-foreground'
                                    "
                                    class="shrink-0 rounded-md px-1.5 py-0.5 text-[10px] font-medium capitalize"
                                >
                                    {{ job.type }}
                                </span>
                            </div>
                            <div
                                class="flex items-center justify-between text-[10px] text-muted-foreground"
                            >
                                <span>By {{ job.creator_name }}</span>
                                <span
                                    class="capitalize"
                                    :class="
                                        job.status === 'open'
                                            ? 'font-medium text-teal-600 dark:text-teal-400'
                                            : 'text-neutral-500'
                                    "
                                >
                                    {{ job.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Events -->
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border/40"
                >
                    <div
                        class="flex items-center justify-between border-b border-sidebar-border/70 pb-4 dark:border-sidebar-border/40"
                    >
                        <div>
                            <h2 class="text-md font-semibold text-foreground">
                                Recent Events
                            </h2>
                            <p class="text-[11px] text-muted-foreground">
                                Public updates and alert feeds.
                            </p>
                        </div>
                        <Button
                            variant="ghost"
                            size="sm"
                            as-child
                            class="h-8 text-xs font-semibold"
                        >
                            <Link :href="eventsIndex()">
                                Manage
                                <ChevronRight class="ml-1 h-3.5 w-3.5" />
                            </Link>
                        </Button>
                    </div>

                    <div class="mt-4 space-y-3.5">
                        <div
                            v-if="recent_events.length === 0"
                            class="py-6 text-center text-xs text-muted-foreground"
                        >
                            No events published yet.
                        </div>

                        <div
                            v-for="evt in recent_events"
                            :key="evt.id"
                            class="flex flex-col gap-1.5 rounded-lg border border-sidebar-border/40 bg-neutral-50/40 p-3 transition-colors duration-150 hover:bg-neutral-50 dark:border-sidebar-border/20 dark:bg-neutral-900/10 dark:hover:bg-neutral-900/30"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <h4
                                    class="line-clamp-1 text-sm font-semibold text-foreground"
                                >
                                    {{ evt.title }}
                                </h4>
                                <span
                                    :class="
                                        typeColors[evt.type] ??
                                        'bg-muted text-[10px] text-muted-foreground'
                                    "
                                    class="shrink-0 rounded-md px-1.5 py-0.5 text-[10px] font-medium capitalize"
                                >
                                    {{ evt.type }}
                                </span>
                            </div>
                            <div
                                class="flex items-center justify-between text-[10px] text-muted-foreground"
                            >
                                <span>By {{ evt.creator_name }}</span>
                                <span
                                    class="font-medium capitalize"
                                    :class="
                                        evt.status === 'active'
                                            ? 'text-green-600 dark:text-green-400'
                                            : 'text-neutral-500'
                                    "
                                >
                                    {{ evt.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
