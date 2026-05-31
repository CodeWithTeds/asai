<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Briefcase, MapPin, Clock } from 'lucide-vue-next';
import { computed, ref, onMounted, onUnmounted } from 'vue';

type JobPosting = {
    id: number;
    title: string;
    description: string;
    location: string | null;
    type: string;
};

const jobPostings = computed<JobPosting[]>(
    () => (usePage().props.jobPostings as JobPosting[]) ?? [],
);

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
        v-if="jobPostings.length > 0"
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
            <div class="job-grid">
                <article
                    v-for="job in jobPostings"
                    :key="job.id"
                    class="job-card"
                >
                    <div class="job-card-header">
                        <div class="job-card-icon">
                            <Briefcase :size="20" />
                        </div>
                        <span class="job-card-type">{{
                            formatType(job.type)
                        }}</span>
                    </div>

                    <h3 class="job-card-title">{{ job.title }}</h3>

                    <p class="job-card-desc">{{ job.description }}</p>

                    <div class="job-card-meta">
                        <span v-if="job.location" class="job-card-location">
                            <MapPin :size="14" />
                            {{ job.location }}
                        </span>
                        <span class="job-card-badge">
                            <Clock :size="14" />
                            {{ formatType(job.type) }}
                        </span>
                    </div>
                </article>
            </div>
        </div>
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

.job-card {
    background: var(--color-bg-elevated);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: 1.75rem;
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

.job-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.job-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    background: linear-gradient(
        135deg,
        var(--color-primary) 0%,
        var(--color-primary-soft) 100%
    );
    color: #fff;
}

.job-card-type {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--color-accent);
    background: rgba(184, 134, 11, 0.08);
    padding: 0.3rem 0.7rem;
    border-radius: 20px;
}

.job-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    margin-bottom: 0.6rem;
    line-height: 1.3;
}

.job-card-desc {
    font-size: 0.88rem;
    color: var(--color-text-muted);
    line-height: 1.7;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 1.25rem;
}

.job-card-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.job-card-location,
.job-card-badge {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    color: var(--color-text-muted);
    font-weight: 500;
}

.job-card-location svg,
.job-card-badge svg {
    color: var(--color-primary-soft);
    flex-shrink: 0;
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
