<script setup lang="ts">
import { useScrollReveal } from '@/composables/useScrollReveal';

const { containerRef } = useScrollReveal();

// Generate the 34 clients programmatically
const clients = Array.from({ length: 34 }, (_, i) => {
    const id = i + 1;
    let gridClass = '';

    if (id === 1) {
        gridClass = 'client-r1-c4';
    } else if (id === 2) {
        gridClass = 'client-r2-c4';
    } else {
        const row = Math.floor((id - 3) / 4) + 3;
        const col = ((id - 3) % 4) + 1;
        gridClass = `client-r${row}-c${col}`;
    }

    return {
        name: `Client ${id}`,
        logo: `/images/clients/client-${id}.jpg`,
        gridClass,
    };
});
</script>

<template>
    <section id="clients" class="clients-section" ref="containerRef">
        <div class="clients-container">
            <div class="clients-grid reveal-stagger">
                <!-- Info Cell -->
                <div class="clients-info-cell reveal">
                    <span class="section-eyebrow">Our Partners</span>
                    <h2 class="section-title">Discover Our Valued Clients</h2>

                    <div class="clients-desc-wrapper">
                        <!-- Yellow pointer SVG -->
                        <div class="clients-desc-pointer">
                            <svg
                                width="12"
                                height="14"
                                viewBox="0 0 12 14"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M0 0L12 7L0 14V0Z"
                                    fill="var(--color-accent-soft)"
                                />
                            </svg>
                        </div>
                        <p class="clients-desc-text">
                            At ASAI, we are proud to serve a diverse and
                            prestigious group of clients, ranging from
                            government agencies and industrial complexes to
                            leading private corporations. Our commitment to
                            excellence, integrity, and proactive protection
                            ensures that our partners can focus on their core
                            operations with absolute peace of mind.
                        </p>
                    </div>
                </div>

                <!-- Client Logo Cards -->
                <div
                    v-for="c in clients"
                    :key="c.name"
                    :class="['client-logo-card', c.gridClass, 'reveal']"
                >
                    <div class="logo-wrapper">
                        <img
                            :src="c.logo"
                            :alt="c.name"
                            class="client-logo-img"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.clients-section {
    background: var(--color-bg-elevated);
    padding: 5rem 1.5rem;
}

.clients-container {
    max-width: var(--container-max);
    margin: 0 auto;
}

.clients-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.clients-info-cell {
    padding: 1rem 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.clients-desc-wrapper {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    margin-top: 1.25rem;
}

.clients-desc-pointer {
    flex-shrink: 0;
    margin-top: 0.35rem;
}

.clients-desc-text {
    font-size: 1rem;
    color: var(--color-text-muted);
    line-height: 1.6;
}

/* Base style for logo cards on mobile */
.client-logo-card {
    background: #ffffff;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    height: 90px;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    z-index: 1;
}

.logo-wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.8;
    filter: grayscale(100%);
    transition: all 0.3s ease;
}

.client-logo-img {
    max-width: 70%;
    max-height: 60%;
    object-fit: contain;
}

/* Hover effects */
.client-logo-card:hover {
    z-index: 2;
    background: #ffffff;
    box-shadow: 0 12px 32px var(--color-shadow);
    transform: translateY(-4px);
    border-color: rgba(29, 33, 157, 0.15);
}

.client-logo-card:hover .logo-wrapper {
    opacity: 1;
    filter: grayscale(0%);
}

/* Responsive grid layouts */
@media (min-width: 640px) {
    .clients-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
}

@media (min-width: 768px) {
    .clients-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .clients-info-cell {
        grid-column: 1 / span 3;
        padding-bottom: 2rem;
    }

    .clients-desc-text {
        font-size: 1.05rem;
    }
}

@media (min-width: 1024px) {
    .clients-section {
        padding: 6rem 2.5rem;
    }

    .clients-grid {
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: minmax(110px, auto);
        gap: 0;
    }

    .clients-info-cell {
        grid-column: 1 / span 2;
        grid-row: 1 / span 2;
        padding-right: 3.5rem;
        padding-bottom: 1rem;
    }

    .client-logo-card {
        border: none;
        border-right: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
        border-radius: 0;
        height: 100%;
    }

    /* Specific grid positions */
    .client-r1-c4 {
        grid-column: 4;
        grid-row: 1;
    }

    .client-r2-c4 {
        grid-column: 4;
        grid-row: 2;
    }

    .client-r3-c1 {
        grid-column: 1;
        grid-row: 3;
    }

    .client-r3-c2 {
        grid-column: 2;
        grid-row: 3;
    }

    .client-r3-c3 {
        grid-column: 3;
        grid-row: 3;
    }

    .client-r3-c4 {
        grid-column: 4;
        grid-row: 3;
    }

    .client-r4-c1 {
        grid-column: 1;
        grid-row: 4;
    }

    .client-r4-c2 {
        grid-column: 2;
        grid-row: 4;
    }

    .client-r4-c3 {
        grid-column: 3;
        grid-row: 4;
    }

    .client-r4-c4 {
        grid-column: 4;
        grid-row: 4;
    }

    .client-r5-c1 {
        grid-column: 1;
        grid-row: 5;
    }

    .client-r5-c2 {
        grid-column: 2;
        grid-row: 5;
    }

    .client-r5-c3 {
        grid-column: 3;
        grid-row: 5;
    }

    .client-r5-c4 {
        grid-column: 4;
        grid-row: 5;
    }

    .client-r6-c1 {
        grid-column: 1;
        grid-row: 6;
    }

    .client-r6-c2 {
        grid-column: 2;
        grid-row: 6;
    }

    .client-r6-c3 {
        grid-column: 3;
        grid-row: 6;
    }

    .client-r6-c4 {
        grid-column: 4;
        grid-row: 6;
    }

    .client-r7-c1 {
        grid-column: 1;
        grid-row: 7;
    }

    .client-r7-c2 {
        grid-column: 2;
        grid-row: 7;
    }

    .client-r7-c3 {
        grid-column: 3;
        grid-row: 7;
    }

    .client-r7-c4 {
        grid-column: 4;
        grid-row: 7;
    }

    .client-r8-c1 {
        grid-column: 1;
        grid-row: 8;
    }

    .client-r8-c2 {
        grid-column: 2;
        grid-row: 8;
    }

    .client-r8-c3 {
        grid-column: 3;
        grid-row: 8;
    }

    .client-r8-c4 {
        grid-column: 4;
        grid-row: 8;
    }

    .client-r9-c1 {
        grid-column: 1;
        grid-row: 9;
    }

    .client-r9-c2 {
        grid-column: 2;
        grid-row: 9;
    }

    .client-r9-c3 {
        grid-column: 3;
        grid-row: 9;
    }

    .client-r9-c4 {
        grid-column: 4;
        grid-row: 9;
    }

    .client-r10-c1 {
        grid-column: 1;
        grid-row: 10;
    }

    .client-r10-c2 {
        grid-column: 2;
        grid-row: 10;
    }

    .client-r10-c3 {
        grid-column: 3;
        grid-row: 10;
    }

    .client-r10-c4 {
        grid-column: 4;
        grid-row: 10;
    }

    /* Top borders */
    .client-r1-c4,
    .client-r3-c1,
    .client-r3-c2,
    .client-r3-c3 {
        border-top: 1px solid var(--color-border);
    }

    /* Left borders */
    .client-r1-c4,
    .client-r2-c4,
    .client-r3-c1,
    .client-r4-c1,
    .client-r5-c1,
    .client-r6-c1,
    .client-r7-c1,
    .client-r8-c1,
    .client-r9-c1,
    .client-r10-c1 {
        border-left: 1px solid var(--color-border);
    }

    /* Outer rounded corners */
    .client-r1-c4 {
        border-top-left-radius: var(--radius-lg);
        border-top-right-radius: var(--radius-lg);
    }

    .client-r3-c1 {
        border-top-left-radius: var(--radius-lg);
    }

    .client-r10-c1 {
        border-bottom-left-radius: var(--radius-lg);
    }

    .client-r10-c4 {
        border-bottom-right-radius: var(--radius-lg);
    }
}

@media (min-width: 1200px) {
    .clients-section {
        padding: 7rem 4rem;
    }
}
</style>
