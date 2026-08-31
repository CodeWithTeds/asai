<script setup lang="ts">
import '../../../css/welcome/main.css';
import { Head, usePage } from '@inertiajs/vue3';
import { Briefcase, CheckCircle2, X } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import AppFooter from '@/components/Welcome/AppFooter.vue';
import AppHeader from '@/components/Welcome/AppHeader.vue';
import ApplyModal from './partials/ApplyModal.vue';

type JobPosting = {
    id: number;
    title: string;
    description: string;
    cover_image: string | null;
    location: string | null;
    type: string;
    created_at: string;
};

const jobPostings = computed<JobPosting[]>(
    () => (usePage().props.jobPostings as JobPosting[]) ?? [],
);

const isApplyModalOpen = ref(false);
const selectedJob = ref<JobPosting | null>(null);

// Custom Page Toast State
const toast = ref<{
    show: boolean;
    title: string;
    message: string;
    jobTitle: string;
}>({
    show: false,
    title: '',
    message: '',
    jobTitle: '',
});

let toastTimeout: ReturnType<typeof setTimeout> | null = null;

function handleApplySuccess(jobTitle: string) {
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }

    toast.value = {
        show: true,
        title: 'Application Submitted Successfully!',
        message: `Your application for "${jobTitle}" has been received. Our recruitment team will review your qualifications and contact you soon.`,
        jobTitle,
    };

    toastTimeout = setTimeout(() => {
        toast.value.show = false;
    }, 6500);
}

function dismissToast() {
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
    toast.value.show = false;
}

function formatDate(dateStr: string) {
    if (!dateStr) {
        return '';
    }

    return new Date(dateStr).toLocaleDateString('en-US', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

function handleApplyClick(job: JobPosting) {
    selectedJob.value = job;
    isApplyModalOpen.value = true;
}

function formatType(type: string) {
    const labels: Record<string, string> = {
        full_time: 'Full Time',
        part_time: 'Part Time',
        contract: 'Contract',
    };

    return labels[type] ?? type;
}

// Scroll-triggered entrance animation
const sectionRef = ref<HTMLElement | null>(null);
const isVisible = ref(false);
let observer: IntersectionObserver | null = null;

onMounted(() => {
    const el = sectionRef.value;

    if (!el) {
        return;
    }

    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry && entry.isIntersecting) {
                isVisible.value = true;
                observer?.disconnect();
            }
        },
        { threshold: 0.15 },
    );
    observer.observe(el);
});

onUnmounted(() => {
    observer?.disconnect();
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
});
</script>

<template>
    <Head>
        <title>Careers | Join Our Team — ASAI</title>
        <meta
            name="description"
            content="Explore career opportunities at Annapolis Security Agency, Inc. (ASAI). Browse active job openings and apply to join our world-class security team."
        />
    </Head>

    <div class="careers-page-wrapper">
        <!-- Main Site Header -->
        <AppHeader />

        <!-- Hero / Page Header -->
        <div class="careers-hero">
            <!-- Decorative blobs -->
            <div class="hero-blob hero-blob-1"></div>
            <div class="hero-blob hero-blob-2"></div>

            <div class="hero-body container">
                <div
                    class="hero-badge"
                    :class="
                        jobPostings.length > 0
                            ? 'hero-badge--open'
                            : 'hero-badge--closed'
                    "
                >
                    <Briefcase :size="14" />
                    <span>{{
                        jobPostings.length > 0
                            ? 'Now Hiring'
                            : 'No Openings at the Moment'
                    }}</span>
                </div>
                <h1 class="hero-heading">Join Our Team</h1>
                <p class="hero-desc">
                    We're looking for talented individuals to help us deliver
                    world-class security services across the Philippines.
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-num">{{
                            jobPostings.length || 0
                        }}</span>
                        <span class="hero-stat-label"
                            >Open Position{{
                                jobPostings.length !== 1 ? 's' : ''
                            }}</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Postings Section -->
        <section
            id="careers"
            ref="sectionRef"
            class="job-postings-section"
            :class="{ 'is-visible': isVisible }"
        >
            <div class="container">
                <!-- Job cards grid -->
                <div v-if="jobPostings.length > 0" class="job-grid">
                    <article
                        v-for="job in jobPostings"
                        :key="job.id"
                        class="job-card"
                    >
                        <!-- Curved Top Box (peach style or cover image bg with dark overlay) -->
                        <div
                            class="job-card-top"
                            :style="{
                                backgroundImage: `url(${job.cover_image ? '/storage/' + job.cover_image : '/images/hero.png'})`,
                            }"
                        >
                            <!-- Dark overlay -->
                            <div class="job-card-top-overlay"></div>

                            <!-- Top Content Row (Date + Icon) -->
                            <div class="job-card-top-row">
                                <span class="job-date-badge">{{
                                    formatDate(job.created_at)
                                }}</span>
                                <div class="job-card-icon-circle">
                                    <Briefcase :size="16" />
                                </div>
                            </div>

                            <!-- Mid Content (Company + Title) -->
                            <div class="job-card-top-content">
                                <span class="job-company">ASAI</span>
                                <h3 class="job-card-title">{{ job.title }}</h3>
                            </div>

                            <!-- Bottom Content Row (Pill Badges / Tags) -->
                            <div class="job-card-tags">
                                <span class="job-tag">{{
                                    formatType(job.type)
                                }}</span>
                                <span v-if="job.location" class="job-tag">{{
                                    job.location
                                }}</span>
                                <span class="job-tag">Active</span>
                            </div>
                        </div>

                        <!-- Bottom White Box -->
                        <div class="job-card-bottom">
                            <!-- Paragraph Description -->
                            <p class="job-card-desc">{{ job.description }}</p>

                            <!-- Footer Actions Row -->
                            <div class="job-card-footer">
                                <div class="job-meta-info">
                                    <span class="job-meta-title"
                                        >Open Position</span
                                    >
                                    <span class="job-meta-sub">{{
                                        job.location || 'Agency'
                                    }}</span>
                                </div>
                                <button
                                    class="apply-btn"
                                    @click="handleApplyClick(job)"
                                >
                                    Apply Now
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Empty state when no job postings -->
                <div v-else class="empty-state">
                    <div class="empty-icon-wrapper">
                        <Briefcase :size="36" />
                    </div>
                    <p class="empty-title">No Active Openings</p>
                    <p class="empty-text">
                        We don't have any job openings right now. Follow us for
                        future updates or check back later!
                    </p>
                </div>
            </div>

            <ApplyModal
                v-model:open="isApplyModalOpen"
                :job="selectedJob"
                @success="handleApplySuccess"
            />
        </section>

        <!-- Custom Careers Page Toast Notification -->
        <Teleport to="body">
            <Transition name="toast-slide">
                <div
                    v-if="toast.show"
                    class="careers-custom-toast"
                    role="alert"
                    aria-live="assertive"
                >
                    <div class="toast-icon-box">
                        <CheckCircle2 :size="20" class="toast-check-icon" />
                    </div>
                    <div class="toast-body">
                        <div class="toast-header-row">
                            <span class="toast-badge">Application Sent</span>
                            <span class="toast-time">Just now</span>
                        </div>
                        <h4 class="toast-heading">{{ toast.title }}</h4>
                        <p class="toast-text">{{ toast.message }}</p>
                    </div>
                    <button
                        type="button"
                        class="toast-dismiss-btn"
                        aria-label="Dismiss notification"
                        @click="dismissToast"
                    >
                        <X :size="16" />
                    </button>
                    <div class="toast-timer-bar"></div>
                </div>
            </Transition>
        </Teleport>

        <!-- Main Site Footer -->
        <AppFooter />
    </div>
</template>

<style scoped>
/* ─── Page wrapper ───────────────────────────────── */
.careers-page-wrapper {
    background: var(--color-bg);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* ─── Hero ───────────────────────────────────────── */
.careers-hero {
    position: relative;
    background: linear-gradient(150deg, #f0f4ff 0%, #fafbff 40%, #f5f8ff 100%);
    border-bottom: 1px solid var(--color-border);
    padding: 2.25rem 0;
    overflow: hidden;
}

@media (min-width: 768px) {
    .careers-hero {
        padding: 3.5rem 0;
    }
}

.hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
}

.hero-blob-1 {
    width: 320px;
    height: 320px;
    background: rgba(29, 33, 157, 0.06);
    top: -80px;
    right: -40px;
}

@media (min-width: 768px) {
    .hero-blob-1 {
        width: 480px;
        height: 480px;
        top: -120px;
        right: -80px;
    }
}

.hero-blob-2 {
    width: 240px;
    height: 240px;
    background: rgba(201, 168, 76, 0.07);
    bottom: -60px;
    left: 5%;
}

@media (min-width: 768px) {
    .hero-blob-2 {
        width: 320px;
        height: 320px;
        bottom: -100px;
    }
}

.hero-body {
    position: relative;
    z-index: 2;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.35rem 0.9rem;
    border-radius: 100px;
    margin-bottom: 1rem;
    transition:
        background 0.3s ease,
        color 0.3s ease,
        border-color 0.3s ease;
}

@media (min-width: 768px) {
    .hero-badge {
        margin-bottom: 1.5rem;
        padding: 0.4rem 1rem;
    }
}

.hero-badge--open {
    background: rgba(29, 33, 157, 0.08);
    border: 1px solid rgba(29, 33, 157, 0.15);
    color: var(--color-primary);
}

.hero-badge--closed {
    background: rgba(100, 116, 139, 0.08);
    border: 1px solid rgba(100, 116, 139, 0.2);
    color: #64748b;
}

.hero-heading {
    font-size: clamp(1.6rem, 5.5vw, 2.4rem);
    font-weight: 800;
    color: var(--color-primary-dark);
    letter-spacing: -0.01em;
    line-height: 1.15;
    margin: 0 0 0.75rem;
}

.hero-desc {
    font-size: 0.95rem;
    color: var(--color-text-muted);
    line-height: 1.6;
    max-width: 520px;
    margin: 0 0 1.75rem;
}

@media (min-width: 768px) {
    .hero-desc {
        font-size: 1.05rem;
        line-height: 1.65;
        margin: 0 0 2.5rem;
    }
}

.hero-stats {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.hero-stat {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.hero-stat-num {
    font-size: 1.35rem;
    font-weight: 900;
    color: var(--color-primary-dark);
    letter-spacing: -0.02em;
    line-height: 1;
}

@media (min-width: 768px) {
    .hero-stat-num {
        font-size: 1.5rem;
    }
}

.hero-stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.hero-stat-divider {
    width: 1px;
    height: 32px;
    background: var(--color-border);
}

/* ─── Job Postings Section ───────────────────────── */
.job-postings-section {
    background: var(--color-bg);
    flex: 1;
    padding: 2.25rem 0 4rem;
}

@media (min-width: 768px) {
    .job-postings-section {
        padding: 3.5rem 0 6rem;
    }
}

.job-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
    grid-auto-rows: 1fr;
    opacity: 0;
    transform: translateY(30px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s,
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
}

@media (min-width: 640px) {
    .job-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (min-width: 1024px) {
    .job-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.job-postings-section.is-visible .job-grid {
    opacity: 1;
    transform: translateY(0);
}

/* Card — flex column so body stretches to fill remaining height */
.job-card {
    display: flex;
    flex-direction: column;
    background: var(--color-bg-elevated);
    border: 1px solid var(--color-border);
    border-radius: 18px;
    padding: 8px;
    overflow: hidden;
    transition:
        transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
        box-shadow 0.3s ease,
        border-color 0.3s ease;
}

@media (min-width: 768px) {
    .job-card {
        border-radius: 24px;
        padding: 10px;
    }
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px var(--color-shadow);
    border-color: rgba(29, 33, 157, 0.15);
}

/* Curved top box with background image and overlay */
.job-card-top {
    position: relative;
    width: 100%;
    height: 180px;
    border-radius: 14px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 1.2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

@media (min-width: 768px) {
    .job-card-top {
        height: 220px;
        padding: 1.5rem;
        border-radius: 18px;
    }
}

/* Dark overlay for readability */
.job-card-top-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.3) 0%,
        rgba(0, 0, 0, 0.65) 100%
    );
    z-index: 1;
}

/* Top Row (Date and Icon) */
.job-card-top-row {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.job-date-badge {
    background: #ffffff;
    color: #0f172a;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

@media (min-width: 768px) {
    .job-date-badge {
        font-size: 0.72rem;
        padding: 0.35rem 0.9rem;
    }
}

.job-card-icon-circle {
    background: #ffffff;
    color: var(--color-primary);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .job-card-icon-circle {
        width: 32px;
        height: 32px;
    }
}

/* Middle Content (Company & Title) */
.job-card-top-content {
    position: relative;
    z-index: 2;
    margin-top: auto;
    margin-bottom: 0.6rem;
}

.job-company {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.8);
    display: block;
    margin-bottom: 0.2rem;
}

.job-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.3;
    margin: 0;
    word-break: break-word;
}

@media (min-width: 768px) {
    .job-card-title {
        font-size: 1.25rem;
    }
}

/* Tags / Pills */
.job-card-tags {
    position: relative;
    z-index: 2;
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
}

.job-tag {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(4px);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 0.68rem;
    font-weight: 600;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    text-transform: capitalize;
}

/* Bottom White Area */
.job-card-bottom {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 1rem 0.65rem 0.4rem;
}

@media (min-width: 768px) {
    .job-card-bottom {
        padding: 1.25rem 0.75rem 0.5rem;
    }
}

.job-card-desc {
    font-size: 0.85rem;
    color: var(--color-text-muted);
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 1rem;
    flex: 1;
    word-break: break-word;
}

/* Footer / Action row */
.job-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    gap: 0.75rem;
}

.job-meta-info {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.job-meta-title {
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--color-primary-dark);
}

.job-meta-sub {
    font-size: 0.72rem;
    color: var(--color-text-muted);
    font-weight: 500;
}

.apply-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.55rem 1.15rem;
    background: var(--color-primary);
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 24px;
    border: none;
    cursor: pointer;
    transition:
        background 0.2s ease,
        transform 0.15s ease;
    white-space: nowrap;
}

.apply-btn:hover {
    background: var(--color-primary-soft);
}

.apply-btn:active {
    transform: scale(0.98);
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 3rem 1.5rem;
    max-width: 480px;
    margin: 0 auto;
    opacity: 0;
    transform: translateY(20px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s,
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
}

@media (min-width: 768px) {
    .empty-state {
        padding: 4rem 2rem;
    }
}

.job-postings-section.is-visible .empty-state {
    opacity: 1;
    transform: translateY(0);
}

.empty-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(29, 33, 157, 0.05);
    color: var(--color-primary);
    margin-bottom: 1rem;
}

@media (min-width: 768px) {
    .empty-icon-wrapper {
        width: 72px;
        height: 72px;
        margin-bottom: 1.25rem;
    }
}

.empty-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    margin-bottom: 0.5rem;
}

.empty-text {
    font-size: 0.88rem;
    color: var(--color-text-muted);
    line-height: 1.55;
}

/* ─── Careers Custom Toast ───────────────────────── */
.careers-custom-toast {
    position: fixed;
    top: 1.25rem;
    right: 1.25rem;
    z-index: 99999;
    max-width: 440px;
    width: calc(100% - 2.5rem);
    background: var(--color-bg-elevated, #ffffff);
    border: 1px solid var(--color-border);
    border-radius: 16px;
    box-shadow:
        0 20px 40px -10px rgba(13, 17, 23, 0.18),
        0 0 0 1px rgba(29, 33, 157, 0.08);
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    padding: 1rem 1.15rem;
    overflow: hidden;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

.toast-icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    flex-shrink: 0;
    margin-top: 0.1rem;
}

.toast-body {
    flex: 1;
    min-width: 0;
}

.toast-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.toast-badge {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.15rem 0.55rem;
    border-radius: 100px;
    background: rgba(29, 33, 157, 0.08);
    color: var(--color-primary, #1d219d);
}

.toast-time {
    font-size: 0.72rem;
    color: var(--color-text-muted, #64748b);
    font-weight: 500;
}

.toast-heading {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--color-primary-dark, #0f172a);
    margin: 0 0 0.25rem;
    line-height: 1.3;
}

.toast-text {
    font-size: 0.82rem;
    color: var(--color-text-muted, #475569);
    line-height: 1.45;
    margin: 0;
    word-break: break-word;
}

.toast-dismiss-btn {
    background: none;
    border: none;
    color: var(--color-text-muted, #94a3b8);
    cursor: pointer;
    padding: 0.3rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: -0.15rem;
    margin-right: -0.25rem;
    transition:
        background 0.2s ease,
        color 0.2s ease;
}

.toast-dismiss-btn:hover {
    background: rgba(0, 0, 0, 0.05);
    color: var(--color-text);
}

.toast-timer-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #1d219d, #c9a84c);
    animation: toastCountdown 6.5s linear forwards;
}

@keyframes toastCountdown {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

/* ─── Mobile Adjustments (<640px) ─────────────────── */
@media (max-width: 640px) {
    .careers-custom-toast {
        top: max(0.85rem, env(safe-area-inset-top, 0.85rem));
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        width: calc(100% - 1.5rem);
        max-width: 420px;
        padding: 0.85rem 0.95rem;
        gap: 0.75rem;
        border-radius: 14px;
        box-shadow:
            0 12px 30px -6px rgba(13, 17, 23, 0.22),
            0 0 0 1px rgba(29, 33, 157, 0.08);
    }

    .toast-icon-box {
        width: 34px;
        height: 34px;
        border-radius: 8px;
    }

    .toast-badge {
        font-size: 0.62rem;
        padding: 0.12rem 0.45rem;
    }

    .toast-time {
        font-size: 0.68rem;
    }

    .toast-heading {
        font-size: 0.88rem;
        margin: 0 0 0.15rem;
    }

    .toast-text {
        font-size: 0.78rem;
        line-height: 1.4;
    }

    .toast-dismiss-btn {
        padding: 0.25rem;
        min-width: 28px;
        min-height: 28px;
    }
}

/* ─── Toast Transitions ───────────────────────────── */
.toast-slide-enter-active,
.toast-slide-leave-active {
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-slide-enter-from {
    opacity: 0;
    transform: translateY(-20px) scale(0.96);
}

.toast-slide-leave-to {
    opacity: 0;
    transform: translateY(-15px) scale(0.96);
}

@media (max-width: 640px) {
    .toast-slide-enter-from {
        opacity: 0;
        transform: translate(-50%, -20px) scale(0.96);
    }

    .toast-slide-leave-to {
        opacity: 0;
        transform: translate(-50%, -15px) scale(0.96);
    }
}
</style>
