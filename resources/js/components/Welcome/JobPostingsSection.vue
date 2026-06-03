<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Briefcase, MapPin, Clock } from 'lucide-vue-next';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import ApplyModal from './ApplyModal.vue';

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

function formatDate(dateStr: string) {
    if (!dateStr) return '';
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

onMounted(() => {
    const el = sectionRef.value;

    if (!el) {
        return;
    }

    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry && entry.isIntersecting) {
                isVisible.value = true;
                observer.disconnect();
            }
        },
        { threshold: 0.15 },
    );
    observer.observe(el);

    onUnmounted(() => observer.disconnect());
});
</script>

<template>
    <section
        id="careers"
        ref="sectionRef"
        class="job-postings-section section"
        :class="{ 'is-visible': isVisible }"
    >
        <div class="container">
            <!-- Section header -->
            <div class="section-header center">
                <span class="section-eyebrow">Careers</span>
                <h2 class="section-title">Join Our Team</h2>
                <p class="section-lead">
                    We're looking for talented individuals to help us deliver
                    world-class security services.
                </p>
            </div>

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
                            backgroundImage: `url(${job.cover_image ? '/storage/' + job.cover_image : '/images/hero.png'})`
                        }"
                    >
                        <!-- Dark overlay -->
                        <div class="job-card-top-overlay"></div>

                        <!-- Top Content Row (Date + Icon) -->
                        <div class="job-card-top-row">
                            <span class="job-date-badge">{{ formatDate(job.created_at) }}</span>
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
                            <span class="job-tag">{{ formatType(job.type) }}</span>
                            <span v-if="job.location" class="job-tag">{{ job.location }}</span>
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
                                <span class="job-meta-title">Open Position</span>
                                <span class="job-meta-sub">{{ job.location || 'Agency' }}</span>
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
                <h3 class="empty-title">No Active Openings</h3>
                <p class="empty-text">
                    We don't have any job openings right now. Follow us for
                    future announcements or check back later!
                </p>
            </div>
        </div>

        <ApplyModal v-model:open="isApplyModalOpen" :job="selectedJob" />
    </section>
</template>

<style scoped>
.job-postings-section {
    background: var(--color-bg);
}

.job-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    /* Equal row heights — each track stretches to match the tallest card */
    grid-auto-rows: 1fr;
    opacity: 0;
    transform: translateY(30px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s,
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
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
    border-radius: 24px;
    padding: 10px;
    overflow: hidden;
    transition:
        transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
        box-shadow 0.3s ease,
        border-color 0.3s ease;
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
    height: 220px;
    border-radius: 18px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
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
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.35rem 0.9rem;
    border-radius: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.job-card-icon-circle {
    background: #ffffff;
    color: var(--color-primary);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    flex-shrink: 0;
}

/* Middle Content (Company & Title) */
.job-card-top-content {
    position: relative;
    z-index: 2;
    margin-top: auto;
    margin-bottom: 0.75rem;
}

.job-company {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.8);
    display: block;
    margin-bottom: 0.25rem;
}

.job-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.3;
    margin: 0;
}

/* Tags / Pills */
.job-card-tags {
    position: relative;
    z-index: 2;
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.job-tag {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(4px);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    text-transform: capitalize;
}

/* Bottom White Area */
.job-card-bottom {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 1.25rem 0.75rem 0.5rem;
}

.job-card-desc {
    font-size: 0.85rem;
    color: var(--color-text-muted);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 1.25rem;
    flex: 1;
    height: 2.7rem;
}

/* Footer / Action row */
.job-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    gap: 1rem;
}

.job-meta-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.job-meta-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: var(--color-primary-dark);
}

.job-meta-sub {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    font-weight: 500;
}

.apply-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.6rem 1.3rem;
    background: var(--color-primary);
    color: #ffffff;
    font-size: 0.82rem;
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
    justify-content: center;
    text-align: center;
    padding: 3.5rem 2rem;
    background: var(--color-bg-elevated);
    border: 1px dashed var(--color-border);
    border-radius: var(--radius-md);
    max-width: 540px;
    margin: 0 auto;
    opacity: 0;
    transform: translateY(20px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s,
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
}

.job-postings-section.is-visible .empty-state {
    opacity: 1;
    transform: translateY(0);
}

.empty-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(29, 33, 157, 0.05);
    color: var(--color-primary);
    margin-bottom: 1.25rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    margin-bottom: 0.5rem;
}

.empty-text {
    font-size: 0.92rem;
    color: var(--color-text-muted);
    line-height: 1.6;
}

/* Responsive */
@media (min-width: 640px) {
    .job-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .job-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
