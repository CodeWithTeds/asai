<script setup lang="ts">
import { Building2, Handshake, ScrollText } from 'lucide-vue-next';
import { useScrollReveal } from '@/composables/useScrollReveal';

const { containerRef } = useScrollReveal();

const milestones = [
    {
        icon: ScrollText,
        title: 'Established in 2014',
        text: 'Founded by Senior Police Officer Atty. Urvi B. Pascual to deliver disciplined, licensed security services.',
        img: '/images/image-4.jpg',
    },
    {
        icon: Building2,
        title: 'SEC Registered & PNP-SOSIA Certified',
        text: 'Registered under SEC CS201809381 with regular in-service compliance, safety seminars, and SOP audits.',
        img: '/images/image-8.jpg',
    },
    {
        icon: Handshake,
        title: 'AsiaLink Affiliate & Nationwide Reach',
        text: 'Affiliated with the AsiaLink Group of Companies, providing robust financial backing and nationwide logistics.',
        img: '/images/image-1.jpg',
    },
];

const news = [
    {
        date: 'August 2026',
        text: 'ASAI tactical security unit completes live-fire marksmanship and safety qualifications at Tanayan Gun Range in Rizal.',
    },
    {
        date: 'July 2026',
        text: 'On-site emergency evacuation and first responder rescue training successfully conducted for industrial plant detachments.',
    },
    {
        date: 'June 2026',
        text: 'Security Detachment 104 completes updated gate access protocols and perimeter security mount at Republic Cement Norzagaray.',
    },
    {
        date: 'May 2026',
        text: 'Commercial and retail security detachment awarded disaster preparedness and customer safety readiness certifications.',
    },
];
</script>

<template>
    <section id="about" class="about-section" ref="containerRef">
        <div class="about-container">
            <!-- Header -->
            <div class="about-header reveal">
                <span class="section-eyebrow">About ASAI</span>
                <h2 class="section-title">
                    A trusted security partner since 2014
                </h2>
                <p class="section-lead">
                    Formerly UBP Security Agency, ASAI is a service-oriented
                    corporation with strong financial capabilities, technically
                    and financially ready to undertake contractual obligations
                    across the government and private sectors.
                </p>
            </div>

            <!-- Two-column layout -->
            <div class="about-grid">
                <!-- Left: Milestone cards with images -->
                <div class="about-left">
                    <h3 class="col-heading">Latest Milestones</h3>
                    <div class="milestone-list">
                        <div
                            v-for="m in milestones"
                            :key="m.title"
                            class="milestone-card reveal"
                        >
                            <div class="milestone-card-img">
                                <img :src="m.img" :alt="m.title" />
                            </div>
                            <div class="milestone-card-info">
                                <span class="milestone-card-title">{{
                                    m.title
                                }}</span>
                                <p class="milestone-card-text">{{ m.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: News list -->
                <div class="about-right">
                    <h3 class="col-heading">News</h3>
                    <div class="news-list">
                        <div
                            v-for="(item, i) in news"
                            :key="i"
                            class="news-item reveal"
                        >
                            <span class="news-date">{{ item.date }}</span>
                            <p class="news-text">{{ item.text }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motto -->
            <p class="motto reveal">
                "TRANSFORMING SECURITY FOR A BETTER BUSINESS" — a guarantee that
                all clients are assured of protection and security with
                dedication and honesty.
            </p>
        </div>
    </section>
</template>

<style scoped>
.about-section {
    background: #f5f7fa;
    padding: 3.5rem 1.25rem;
}

.about-container {
    max-width: 1200px;
    margin: 0 auto;
}

.about-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.about-header .section-title {
    font-size: clamp(1.6rem, 5vw, 2.75rem);
    line-height: 1.2;
}

.about-header .section-lead {
    max-width: 700px;
    margin: 0.75rem auto 0;
    color: var(--color-text-muted);
    font-size: 0.95rem;
    line-height: 1.65;
}

/* Two-column grid */
.about-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2.5rem;
    margin-bottom: 2.5rem;
}

.col-heading {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--color-primary-dark);
    margin-bottom: 1rem;
}

/* Left column - milestone cards */
.milestone-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.milestone-card {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.06);
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
    cursor: default;
}

.milestone-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.milestone-card-img {
    width: 90px;
    height: 75px;
    flex-shrink: 0;
    overflow: hidden;
}

.milestone-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.milestone-card:hover .milestone-card-img img {
    transform: scale(1.05);
}

.milestone-card-info {
    padding: 0.6rem 0.85rem 0.6rem 0;
}

.milestone-card-title {
    display: block;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    margin-bottom: 0.2rem;
    line-height: 1.3;
}

.milestone-card-text {
    font-size: 0.82rem;
    color: var(--color-text-muted);
    line-height: 1.45;
}

/* Right column - news */
.news-list {
    display: flex;
    flex-direction: column;
}

.news-item {
    padding: 1rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

.news-item:first-child {
    padding-top: 0;
}

.news-item:last-child {
    border-bottom: none;
}

.news-date {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--color-accent);
    margin-bottom: 0.25rem;
}

.news-text {
    font-size: 0.92rem;
    color: var(--color-text);
    line-height: 1.55;
}

/* Motto */
.motto {
    text-align: center;
    font-style: italic;
    font-size: 0.95rem;
    color: var(--color-primary);
    max-width: 760px;
    margin: 0 auto;
    padding-top: 1.75rem;
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    line-height: 1.6;
}

/* Responsive */
@media (min-width: 768px) {
    .about-section {
        padding: 5rem 2.5rem;
    }

    .about-header {
        margin-bottom: 3rem;
    }

    .about-header .section-lead {
        font-size: 1.1rem;
        line-height: 1.7;
    }

    .about-grid {
        grid-template-columns: 1.2fr 1fr;
        gap: 4rem;
        margin-bottom: 3rem;
    }

    .col-heading {
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .milestone-card {
        gap: 1rem;
    }

    .milestone-card-img {
        width: 110px;
        height: 80px;
    }

    .milestone-card-info {
        padding: 0.75rem 1rem 0.75rem 0;
    }

    .milestone-card-title {
        font-size: 1.05rem;
        margin-bottom: 0.25rem;
    }

    .milestone-card-text {
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .news-item {
        padding: 1.25rem 0;
    }

    .news-date {
        font-size: 0.9rem;
        color: var(--color-text-muted);
        margin-bottom: 0.3rem;
    }

    .news-text {
        font-size: 1.05rem;
        line-height: 1.6;
    }

    .motto {
        font-size: 1.15rem;
        padding-top: 2rem;
    }
}

@media (min-width: 1200px) {
    .about-section {
        padding: 6rem 4rem;
    }

    .about-grid {
        gap: 5rem;
    }

    .milestone-card-img {
        width: 130px;
        height: 90px;
    }
}
</style>
