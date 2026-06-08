<script setup lang="ts">
import {
    FileCheck2,
    Crown,
    Layers,
    GraduationCap,
    BadgeCheck,
    Network,
    Truck,
    Landmark,
} from 'lucide-vue-next';
import { useScrollReveal } from '@/composables/useScrollReveal';

const { containerRef } = useScrollReveal();

const capabilities = [
    {
        icon: FileCheck2,
        title: 'Audited Financial Transparency',
        text: 'Strong financial statements regularly audited by Reyes Tacandong & Co., ensuring transparency, compliance, and credibility with clients, partners, and investors.',
        bentoClass: 'bento-financial',
        graphic: 'chart',
    },
    {
        icon: Crown,
        title: 'Experienced Leadership',
        text: 'Led by seasoned professionals with extensive experience in security operations, law enforcement coordination, and corporate management.',
        bentoClass: 'bento-leadership',
        graphic: 'none',
    },
    {
        icon: Layers,
        title: 'Scalable Security Operations',
        text: 'Operational capability to deploy security personnel efficiently across multiple sites nationwide — supporting both small and large-scale requirements.',
        bentoClass: 'bento-scalable',
        graphic: 'none',
    },
    {
        icon: GraduationCap,
        title: 'Highly Trained Personnel',
        text: 'Officers undergo rigorous recruitment screening, training, and continuous professional development to meet the highest standards of operational readiness.',
        bentoClass: 'bento-personnel',
        graphic: 'none',
    },
    {
        icon: BadgeCheck,
        title: 'PNP-SOSIA Licensed Training',
        text: 'A dedicated Training Director licensed by PNP-SOSIA ensures all personnel programs comply with national regulatory standards and industry requirements.',
        bentoClass: 'bento-license',
        graphic: 'badge',
    },
    {
        icon: Network,
        title: 'Integrated Security Solutions',
        text: 'Beyond manpower deployment: risk assessment, security planning, incident management, and coordination with local authorities.',
        bentoClass: 'bento-solutions',
        graphic: 'none',
    },
    {
        icon: Truck,
        title: 'Logistically Prepared',
        text: 'Adequate logistical resources including mobility, communication systems, and operational support for rapid deployment and emergency response.',
        bentoClass: 'bento-logistics',
        graphic: 'network',
    },
    {
        icon: Landmark,
        title: 'Government & Law Enforcement',
        text: 'Established coordination with PNP, NBI, MMDA, and other national authorities for efficient incident handling and security operations.',
        bentoClass: 'bento-gov',
        graphic: 'shield',
    },
];
</script>

<template>
    <section id="capabilities" class="capabilities-section" ref="containerRef">
        <div class="cap-container">
            <!-- Header -->
            <div class="cap-header reveal">
                <span class="section-eyebrow">Agency Capability</span>
                <h2 class="section-title">
                    Built on capability, governed by integrity
                </h2>
                <p class="cap-subtitle">
                    Eight pillars that define how we operate, recruit, train,
                    and protect.
                </p>
            </div>

            <!-- Bento Grid Display -->
            <div class="cap-grid reveal-stagger">
                <div
                    v-for="(c, i) in capabilities"
                    :key="c.title"
                    :class="['cap-card', c.bentoClass, 'reveal']"
                >
                    <!-- Background Graphics -->
                    <div
                        v-if="c.graphic === 'chart'"
                        class="bento-bg-graphic chart-graphic"
                    >
                        <svg
                            width="200"
                            height="100"
                            viewBox="0 0 200 100"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M0 80C30 70 40 40 70 55C100 70 120 25 150 35C180 45 190 15 200 20"
                                stroke="rgba(201, 168, 76, 0.2)"
                                stroke-width="3"
                                stroke-linecap="round"
                            />
                            <path
                                d="M0 80C30 70 40 40 70 55C100 70 120 25 150 35C180 45 190 15 200 20V100H0V80Z"
                                fill="url(#chart-grad)"
                                opacity="0.05"
                            />
                            <defs>
                                <linearGradient
                                    id="chart-grad"
                                    x1="100"
                                    y1="20"
                                    x2="100"
                                    y2="100"
                                    gradientUnits="userSpaceOnUse"
                                >
                                    <stop stop-color="var(--color-accent)" />
                                    <stop
                                        offset="1"
                                        stop-color="var(--color-accent)"
                                        stop-opacity="0"
                                    />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <div
                        v-if="c.graphic === 'badge'"
                        class="bento-bg-graphic badge-graphic"
                    >
                        <svg
                            width="120"
                            height="120"
                            viewBox="0 0 120 120"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            opacity="0.1"
                        >
                            <circle
                                cx="60"
                                cy="60"
                                r="50"
                                stroke="#ffffff"
                                stroke-width="2"
                                stroke-dasharray="4 4"
                            />
                            <polygon
                                points="60,25 70,45 92,45 75,58 81,80 60,67 39,80 45,58 28,45 50,45"
                                fill="#ffffff"
                            />
                        </svg>
                    </div>

                    <div
                        v-if="c.graphic === 'network'"
                        class="bento-bg-graphic network-graphic"
                    >
                        <svg
                            width="150"
                            height="120"
                            viewBox="0 0 150 120"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            opacity="0.08"
                        >
                            <line
                                x1="20"
                                y1="20"
                                x2="130"
                                y2="20"
                                stroke="#ffffff"
                                stroke-width="1.5"
                            />
                            <line
                                x1="20"
                                y1="20"
                                x2="75"
                                y2="90"
                                stroke="#ffffff"
                                stroke-width="1.5"
                            />
                            <line
                                x1="130"
                                y1="20"
                                x2="75"
                                y2="90"
                                stroke="#ffffff"
                                stroke-width="1.5"
                            />
                            <circle
                                cx="20"
                                cy="20"
                                r="6"
                                fill="var(--color-accent)"
                            />
                            <circle
                                cx="130"
                                cy="20"
                                r="6"
                                fill="var(--color-accent)"
                            />
                            <circle cx="75" cy="90" r="6" fill="#ffffff" />
                        </svg>
                    </div>

                    <div
                        v-if="c.graphic === 'shield'"
                        class="bento-bg-graphic shield-graphic"
                    >
                        <svg
                            width="120"
                            height="120"
                            viewBox="0 0 120 120"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            opacity="0.05"
                        >
                            <path
                                d="M60 15L15 35V75C15 95 60 110 60 110C60 110 105 95 105 75V35L60 15Z"
                                stroke="var(--color-primary)"
                                stroke-width="2"
                            />
                            <path
                                d="M60 25V100"
                                stroke="var(--color-primary)"
                                stroke-width="1"
                                stroke-dasharray="2 2"
                            />
                        </svg>
                    </div>

                    <!-- Card Top: Icon and Number -->
                    <div class="cap-card-top">
                        <div class="cap-card-icon">
                            <component :is="c.icon" :size="24" />
                        </div>
                        <span class="cap-card-number">
                            {{ String(i + 1).padStart(2, '0') }}
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="cap-card-content">
                        <h3 class="cap-card-title">{{ c.title }}</h3>
                        <p class="cap-card-text">{{ c.text }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.capabilities-section {
    width: 100%;
    background: var(--color-bg);
    padding: 5rem 1.5rem;
}

.cap-container {
    width: 100%;
    max-width: var(--container-max);
    margin: 0 auto;
}

/* Header */
.cap-header {
    text-align: center;
    margin-bottom: 4rem;
}

.cap-header .section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    color: var(--color-primary-dark);
}

.cap-subtitle {
    color: var(--color-text-muted);
    font-size: clamp(0.95rem, 1.3vw, 1.15rem);
    margin-top: 0.5rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Bento Grid */
.cap-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

/* Base Card Style */
.cap-card {
    position: relative;
    background: #ffffff;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 2.25rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    overflow: hidden;
    transition:
        transform 0.45s cubic-bezier(0.16, 1, 0.3, 1),
        box-shadow 0.45s cubic-bezier(0.16, 1, 0.3, 1),
        border-color 0.4s ease;
    cursor: default;
}

.cap-card:hover {
    transform: translateY(-8px) scale(1.015);
    box-shadow: 0 20px 40px var(--color-shadow);
    border-color: rgba(29, 33, 157, 0.15);
}

/* Card Top Row */
.cap-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.cap-card-icon {
    background: var(--color-primary);
    color: #ffffff;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 48px;
    height: 48px;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 12px rgba(29, 33, 157, 0.1);
}

.cap-card:hover .cap-card-icon {
    transform: scale(1.1) rotate(4deg);
}

.cap-card-number {
    font-family: var(--font-display);
    color: rgba(74, 95, 128, 0.1);
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
    user-select: none;
    transition:
        transform 0.4s ease,
        color 0.4s ease;
}

.cap-card:hover .cap-card-number {
    transform: translateY(-2px);
    color: rgba(184, 134, 11, 0.18);
}

/* Card Content Area */
.cap-card-content {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    position: relative;
    z-index: 2;
    flex-grow: 1;
}

.cap-card-title {
    font-family: var(--font-sans);
    color: var(--color-primary-dark);
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1.35;
    transition: color 0.3s ease;
}

.cap-card:hover .cap-card-title {
    color: var(--color-primary);
}

.cap-card-text {
    color: var(--color-text-muted);
    font-size: 0.95rem;
    line-height: 1.65;
}

/* Background Graphics Placement */
.bento-bg-graphic {
    position: absolute;
    right: 0;
    bottom: 0;
    z-index: 1;
    pointer-events: none;
    transition: transform 0.45s ease;
}

.cap-card:hover .bento-bg-graphic {
    transform: scale(1.05) translate(-4px, -4px);
}

/* Specific Panel Designs for Bento Layout */

/* 1. Leadership (Navy Solid Panel) */
.bento-leadership {
    background: linear-gradient(
        135deg,
        var(--color-primary-dark) 0%,
        var(--color-primary) 100%
    );
    border-color: transparent;
}

.bento-leadership .cap-card-title {
    color: #ffffff;
}

.bento-leadership .cap-card-text {
    color: rgba(255, 255, 255, 0.75);
}

.bento-leadership .cap-card-icon {
    background: #ffffff;
    color: var(--color-primary);
}

.bento-leadership .cap-card-number {
    color: rgba(255, 255, 255, 0.08);
}

.bento-leadership:hover .cap-card-number {
    color: rgba(201, 168, 76, 0.15);
}

/* 2. License (Gold Gradient Panel) */
.bento-license {
    background: linear-gradient(135deg, #c9a84c 0%, #b8940e 100%);
    border-color: transparent;
}

.bento-license .cap-card-title {
    color: #ffffff;
}

.bento-license .cap-card-text {
    color: rgba(255, 255, 255, 0.85);
}

.bento-license .cap-card-icon {
    background: var(--color-primary-dark);
    color: #ffffff;
}

.bento-license .cap-card-number {
    color: rgba(255, 255, 255, 0.12);
}

.bento-license:hover .cap-card-number {
    color: rgba(13, 27, 75, 0.2);
}

/* 3. Logistics (Dark Slate/Navy Panel) */
.bento-logistics {
    background: #0d1b4b;
    border-color: transparent;
}

.bento-logistics .cap-card-title {
    color: #ffffff;
}

.bento-logistics .cap-card-text {
    color: rgba(255, 255, 255, 0.75);
}

.bento-logistics .cap-card-icon {
    background: var(--color-accent);
    color: #ffffff;
}

.bento-logistics .cap-card-number {
    color: rgba(255, 255, 255, 0.08);
}

.bento-logistics:hover .cap-card-number {
    color: rgba(201, 168, 76, 0.15);
}

/* Responsive Breakpoints & Desktop Bento Grid Spans */
@media (min-width: 640px) {
    .cap-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (min-width: 768px) {
    /* In tablet, wider panels span full width */
    .bento-financial,
    .bento-license,
    .bento-logistics,
    .bento-gov {
        grid-column: span 2;
    }
}

@media (min-width: 1024px) {
    .capabilities-section {
        padding: 6rem 2.5rem;
    }

    .cap-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    /* Bento Spans:
       Row 1: Financial (span 2), Leadership (span 1), Scalable (span 1) -> 4 cols
       Row 2: Personnel (span 1), License (span 2), Solutions (span 1) -> 4 cols
       Row 3: Logistics (span 2), Gov (span 2) -> 4 cols
    */
    .bento-financial {
        grid-column: span 2;
    }

    .bento-leadership {
        grid-column: span 1;
    }

    .bento-scalable {
        grid-column: span 1;
    }

    .bento-personnel {
        grid-column: span 1;
    }

    .bento-license {
        grid-column: span 2;
    }

    .bento-solutions {
        grid-column: span 1;
    }

    .bento-logistics {
        grid-column: span 2;
    }

    .bento-gov {
        grid-column: span 2;
    }
}

@media (min-width: 1200px) {
    .capabilities-section {
        padding: 7rem 4rem;
    }

    .cap-grid {
        gap: 2rem;
    }
}
</style>
