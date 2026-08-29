<script setup lang="ts">
import { CheckCircle2 } from 'lucide-vue-next';
import { useScrollReveal } from '@/composables/useScrollReveal';

const { containerRef } = useScrollReveal();

const practices = [
    'Physical Security Measures',
    'Security Management Planning',
    'Contract Management',
    'Asset Identification',
    'Control and Monitoring',
    'Uniformed Guard Services',
];
</script>

<template>
    <section class="section quality" ref="containerRef">
        <div class="quality-grid container">
            <div class="reveal-left reveal">
                <span class="section-eyebrow">Our Commitment</span>
                <h2 class="section-title">Excellence, audited end-to-end.</h2>
                <p class="quality-lead">
                    To guarantee consistent quality, we maintain both internal
                    and external audit teams dedicated to monitoring and
                    upholding our standards. We proudly serve clients across
                    Luzon, Visayas, and Mindanao.
                </p>
                <p class="quality-text">
                    The Agency continuously develops and upgrades its systematic
                    security approach through personnel orientation and
                    training, advancement of physical and electronic prevention
                    and detection systems, and improved surveillance and
                    protective security — ensuring continuity of efficient and
                    quality service.
                </p>
            </div>

            <div class="practice-card reveal-right reveal hover-lift">
                <h3 class="practice-title">Key Practices</h3>
                <ul class="practice-list">
                    <li v-for="p in practices" :key="p">
                        <CheckCircle2 :size="18" class="practice-icon" />
                        <span>{{ p }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>

<style scoped>
.quality {
    background: #fff;
}

.quality-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    align-items: center;
}

.quality-lead {
    color: var(--color-text-muted);
    font-size: 0.95rem;
    line-height: 1.65;
    margin-bottom: 0.85rem;
}

.quality-text {
    color: var(--color-text-muted);
    font-size: 0.9rem;
    line-height: 1.6;
}

.practice-card {
    background: linear-gradient(160deg, var(--color-bg) 0%, #ffffff 100%);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: 1.5rem 1.25rem;
    box-shadow: 0 12px 30px var(--color-shadow);
}

.practice-title {
    font-family: var(--font-sans);
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-accent);
    margin-bottom: 1rem;
}

.practice-list {
    list-style: none;
    display: grid;
    gap: 0.75rem;
}

.practice-list li {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--color-primary-dark);
    line-height: 1.4;
}

.practice-icon {
    color: var(--color-accent);
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .quality-lead {
        font-size: 1.05rem;
        margin-bottom: 1rem;
    }

    .practice-card {
        padding: 2rem;
        border-radius: var(--radius-lg);
    }

    .practice-title {
        font-size: 1rem;
        margin-bottom: 1.25rem;
    }

    .practice-list {
        gap: 0.85rem;
    }

    .practice-list li {
        font-size: 0.95rem;
        gap: 0.75rem;
    }
}

@media (min-width: 900px) {
    .quality-grid {
        grid-template-columns: 1.1fr 1fr;
        gap: 4rem;
    }
}
</style>
