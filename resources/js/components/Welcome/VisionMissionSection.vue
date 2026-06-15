<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const visionPoints = [
    'A world-class Security Service Company',
    'An ISO-Certified Security Management Company',
    'To initiate the professionalization of enterprise security practice in the country — creating an environment of professional acceptance of holistic security concepts.',
    'To develop and maintain a secure environment where maximum productivity is assured and people may enjoy the fruits of their labor free of anxiety and fear.',
];

const values = ['Integrity', 'Excellence', 'Commitment', 'Professionalism'];

const panels = ref<HTMLElement[]>([]);

function setPanelRef(el: any, index: number) {
    if (el) {
        panels.value[index] = el;
    }
}

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                }
            });
        },
        { threshold: 0.15 },
    );

    panels.value.forEach((panel) => {
        if (panel) {
            observer.observe(panel);
        }
    });

    onUnmounted(() => observer.disconnect());
});
</script>

<template>
    <div id="vision" class="vmv-wrapper">
        <!-- Vision -->
        <section class="vmv-section vmv-section--vision">
            <div class="vmv-overlay"></div>
            <div class="vmv-content" :ref="(el) => setPanelRef(el, 0)">
                <span class="vmv-eyebrow">Our Vision</span>
                <h2 class="vmv-heading">
                    Setting the standard<br />for security excellence
                </h2>
                <ul class="vmv-list">
                    <li v-for="(point, i) in visionPoints" :key="i">
                        {{ point }}
                    </li>
                </ul>
            </div>
        </section>

        <!-- Mission -->
        <section class="vmv-section vmv-section--mission">
            <div class="vmv-overlay"></div>
            <div class="vmv-content" :ref="(el) => setPanelRef(el, 1)">
                <span class="vmv-eyebrow">Our Mission</span>
                <h2 class="vmv-heading">
                    A trustworthy partner<br />for business development
                </h2>
                <p class="vmv-text">
                    To upgrade the level of security service by applying an
                    integrated system approach for greater specialization —
                    adapting to current and evolving security and safety
                    challenges, making us a trustworthy partner for business
                    development of our current and future clients.
                </p>
            </div>
        </section>

        <!-- Values -->
        <section class="vmv-section vmv-section--values">
            <div class="vmv-overlay"></div>
            <div class="vmv-content" :ref="(el) => setPanelRef(el, 2)">
                <span class="vmv-eyebrow">Our Core Values</span>
                <h2 class="vmv-heading">
                    The principles that<br />drive everything we do
                </h2>
                <div class="vmv-values-list">
                    <span
                        v-for="(val, i) in values"
                        :key="i"
                        class="vmv-value-tag"
                        >{{ val }}</span
                    >
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.vmv-wrapper {
    position: relative;
}

.vmv-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

.vmv-section--vision {
    background-image: url('/images/parralax1.jpg');
}

.vmv-section--mission {
    background-image: url('/images/parralax2.jpg');
}

.vmv-section--values {
    background-image: url('/images/parralax3.jpg');
}

/* Dark overlay */
.vmv-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    z-index: 1;
}

/* Content aligned to the left */
.vmv-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    padding: 4rem 2rem;
    margin-left: 5%;
    opacity: 0;
    transform: translateY(40px);
    transition:
        opacity 0.9s ease,
        transform 0.9s ease;
}

.vmv-content.in-view {
    opacity: 1;
    transform: translateY(0);
}

/* Eyebrow label */
.vmv-eyebrow {
    display: inline-block;
    font-family: var(--font-sans);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--color-accent);
    margin-bottom: 1rem;
}

/* Heading */
.vmv-heading {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    letter-spacing: -0.02em;
}

/* Vision list */
.vmv-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.vmv-list li {
    font-family: var(--font-sans);
    color: rgba(255, 255, 255, 0.85);
    font-size: 1.05rem;
    line-height: 1.7;
    padding-left: 0;
}

/* Mission text */
.vmv-text {
    font-family: var(--font-sans);
    color: rgba(255, 255, 255, 0.85);
    font-size: 1.05rem;
    line-height: 1.8;
}

/* Values tags */
.vmv-values-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.vmv-value-tag {
    display: inline-block;
    font-family: var(--font-sans);
    padding: 0.6rem 1.4rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 4px;
    color: #fff;
    font-size: 0.95rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    transition:
        background 0.3s ease,
        border-color 0.3s ease;
}

.vmv-value-tag:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--color-accent);
}

@media (min-width: 768px) {
    .vmv-content {
        padding: 6rem 4rem;
        margin-left: 8%;
    }
}

@media (min-width: 1200px) {
    .vmv-content {
        margin-left: 10%;
    }
}
</style>
