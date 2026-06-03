<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { DownloadIcon, ArrowLeftIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import jobApplicationsRoutes from '@/routes/job-applications';
import type { JobApplication } from '@/types';

const props = defineProps<{
    jobApplication: JobApplication;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Applications', href: jobApplicationsRoutes.index().url },
            { title: 'Details', href: '' },
        ],
    },
});

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function formatEducation(level: string | null) {
    if (!level) {
        return '—';
    }

    const labels: Record<string, string> = {
        high_school: 'High School Graduate',
        vocational: 'Vocational Graduate',
        college_undergrad: 'College Undergrad',
        college_graduate: 'College Graduate',
        post_graduate: 'Post Graduate',
    };

    return labels[level] ?? level;
}

function formatLicenseDate(date: string | null) {
    if (!date) {
        return '—';
    }

    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
</script>

<template>
    <Head
        :title="`Application Details - ${props.jobApplication.applicant_name}`"
    />

    <div class="flex flex-col gap-6 p-4">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="icon" as-child class="h-8 w-8">
                    <Link :href="jobApplicationsRoutes.index().url">
                        <ArrowLeftIcon class="size-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-xl font-semibold">
                        {{ props.jobApplication.applicant_name }}
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Submitted for
                        <span class="font-medium text-foreground">{{
                            props.jobApplication.job_posting?.title ??
                            'Unknown Job'
                        }}</span>
                    </p>
                </div>
            </div>

            <Button variant="outline" size="sm" as-child>
                <a
                    :href="`/job-applications/${props.jobApplication.id}/resume?download=1`"
                    download
                    target="_blank"
                    class="flex items-center gap-1.5"
                >
                    <DownloadIcon class="size-4" />
                    Download Resume
                </a>
            </Button>
        </div>

        <!-- Main Content Split Grid -->
        <div class="grid grid-cols-1 items-stretch gap-6 lg:grid-cols-12">
            <!-- Left Panel - Info & Cover Letter (5 Cols) -->
            <div class="flex flex-col gap-6 lg:col-span-5">
                <!-- Info Card -->
                <div
                    class="space-y-4 rounded-lg border bg-card p-5 text-card-foreground shadow-sm"
                >
                    <!-- Section 1: Personal Details -->
                    <div>
                        <h3
                            class="text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                        >
                            Candidate Profile
                        </h3>
                        <div class="mt-3 space-y-2">
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Full Name
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{ props.jobApplication.applicant_name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Email Address
                                </p>
                                <a
                                    :href="`mailto:${props.jobApplication.applicant_email}`"
                                    class="text-sm font-medium text-primary hover:underline"
                                >
                                    {{ props.jobApplication.applicant_email }}
                                </a>
                            </div>
                            <div v-if="props.jobApplication.applicant_phone">
                                <p class="text-xs text-muted-foreground">
                                    Phone Number
                                </p>
                                <a
                                    :href="`tel:${props.jobApplication.applicant_phone}`"
                                    class="text-sm font-medium text-foreground hover:underline"
                                >
                                    {{ props.jobApplication.applicant_phone }}
                                </a>
                            </div>
                            <div
                                v-if="props.jobApplication.residential_address"
                            >
                                <p class="text-xs text-muted-foreground">
                                    Residential Address
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{
                                        props.jobApplication.residential_address
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Qualifications -->
                    <div class="border-t pt-4">
                        <h3
                            class="text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                        >
                            Qualifications
                        </h3>
                        <div class="mt-3 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Education
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{
                                        formatEducation(
                                            props.jobApplication
                                                .education_level,
                                        )
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Experience
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{
                                        props.jobApplication.years_of_experience
                                    }}
                                    Years
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Credentials & Physical Specs -->
                    <div class="border-t pt-4">
                        <h3
                            class="text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                        >
                            Credentials & Specs
                        </h3>
                        <div class="mt-3 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Height
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{
                                        props.jobApplication.height_cm
                                            ? `${props.jobApplication.height_cm} cm`
                                            : '—'
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Weight
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{
                                        props.jobApplication.weight_kg
                                            ? `${props.jobApplication.weight_kg} kg`
                                            : '—'
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-muted-foreground">
                                Security License
                            </p>
                            <div
                                v-if="props.jobApplication.has_license"
                                class="mt-1 space-y-1 rounded border bg-muted/30 p-2"
                            >
                                <p
                                    class="text-xs font-semibold text-foreground"
                                >
                                    License No:
                                    <span class="font-normal">{{
                                        props.jobApplication.license_number
                                    }}</span>
                                </p>
                                <p
                                    class="text-xs font-semibold text-foreground"
                                >
                                    Expires:
                                    <span class="font-normal">{{
                                        formatLicenseDate(
                                            props.jobApplication.license_expiry,
                                        )
                                    }}</span>
                                </p>
                            </div>
                            <p
                                v-else
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Unlicensed
                            </p>
                        </div>
                    </div>

                    <!-- Section 4: Application Meta -->
                    <div class="border-t pt-4">
                        <h3
                            class="text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                        >
                            Application Meta
                        </h3>
                        <div class="mt-3 space-y-2">
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Submitted At
                                </p>
                                <p class="text-sm font-medium text-foreground">
                                    {{
                                        formatDate(
                                            props.jobApplication.created_at,
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter Card -->
                <div
                    class="rounded-lg border bg-card p-5 text-card-foreground shadow-sm"
                >
                    <h3
                        class="mb-3 text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Cover Letter
                    </h3>
                    <div
                        class="text-sm leading-relaxed whitespace-pre-wrap text-foreground"
                    >
                        {{
                            props.jobApplication.cover_letter ||
                            'No cover letter was supplied by the applicant.'
                        }}
                    </div>
                </div>

                <!-- References Card -->
                <div
                    v-if="props.jobApplication.references"
                    class="rounded-lg border bg-card p-5 text-card-foreground shadow-sm"
                >
                    <h3
                        class="mb-3 text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Character References
                    </h3>
                    <div
                        class="text-sm leading-relaxed whitespace-pre-wrap text-foreground"
                    >
                        {{ props.jobApplication.references }}
                    </div>
                </div>
            </div>

            <!-- Right Panel - Secure Resume Viewer (7 Cols) -->
            <div
                class="flex h-[calc(100vh-120px)] min-h-[900px] flex-col lg:col-span-7"
            >
                <div
                    class="relative flex h-full flex-1 items-center justify-center overflow-hidden rounded-lg border bg-muted/20 shadow-sm"
                >
                    <iframe
                        v-if="
                            props.jobApplication.resume_path
                                .toLowerCase()
                                .endsWith('.pdf')
                        "
                        :src="`/job-applications/${props.jobApplication.id}/resume#view=Fit&toolbar=0&navpanes=0`"
                        class="h-full w-full border-none"
                    ></iframe>
                    <div v-else class="space-y-2 p-6 text-center">
                        <p class="text-base font-semibold text-foreground">
                            Preview not available for Word documents
                        </p>
                        <p class="text-sm text-muted-foreground">
                            Please use the Download button at the top to review
                            the file
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
