<script setup lang="ts">
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
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
import { onMounted, onUnmounted, ref } from 'vue';

gsap.registerPlugin(ScrollTrigger);

const capabilities = [
    {
        icon: FileCheck2,
        title: 'Audited Financial Transparency',
        text: 'Strong financial statements regularly audited by Reyes Tacandong & Co., ensuring transparency, compliance, and credibility with clients, partners, and investors.',
    },
    {
        icon: Crown,
        title: 'Experienced Leadership',
        text: 'Led by seasoned professionals with extensive experience in security operations, law enforcement coordination, and corporate management.',
    },
    {
        icon: Layers,
        title: 'Scalable Security Operations',
        text: 'Operational capability to deploy security personnel efficiently across multiple sites nationwide — supporting both small and large-scale requirements.',
    },
    {
        icon: GraduationCap,
        title: 'Highly Trained Personnel',
        text: 'Officers undergo rigorous recruitment screening, training, and continuous professional development to meet the highest standards of operational readiness.',
    },
    {
        icon: BadgeCheck,
        title: 'PNP-SOSIA Licensed Training',
        text: 'A dedicated Training Director licensed by PNP-SOSIA ensures all personnel programs comply with national regulatory standards and industry requirements.',
    },
    {
        icon: Network,
        title: 'Integrated Security Solutions',
        text: 'Beyond manpower deployment: risk assessment, security planning, incident management, and coordination with local authorities.',
    },
    {
        icon: Truck,
        title: 'Logistically Prepared',
        text: 'Adequate logistical resources including mobility, communication systems, and operational support for rapid deployment and emergency response.',
    },
    {
        icon: Landmark,
        title: 'Government & Law Enforcement',
        text: 'Established coordination with PNP, NBI, MMDA, and other national authorities for efficient incident handling and security operations.',
    },
];

const sectionRef = ref<HTMLElement | null>(null);
const currentIndex = ref(0);
const animDir = ref<'left' | 'center' | 'right'>('center');

let ctx: gsap.Context | undefined;

onMounted(() => {
    const el = sectionRef.value;

    if (!el) {
return;
}

    ctx = gsap.context(() => {
        const totalItems = capabilities.length;

        ScrollTrigger.create({
            trigger: el,
            start: 'top top',
            end: `+=${totalItems * 50}%`,
            pin: true,
            scrub: 0.4,
            onUpdate: (self) => {
                const progress = self.progress;
                const newIndex = Math.min(
                    Math.floor(progress * totalItems),
                    totalItems - 1,
                );

                if (newIndex !== currentIndex.value) {
                    animDir.value =
                        newIndex > currentIndex.value ? 'right' : 'left';
                    currentIndex.value = newIndex;
                }

                // Update dots
                const dots = el.querySelectorAll('.cap-dot');
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex.value);
                    dot.classList.toggle('visited', i < currentIndex.value);
                });
            },
        });

        // Refresh after fonts load to fix layout offsets in Laravel/Inertia
        if ('fonts' in document) {
            document.fonts.ready.then(() => ScrollTrigger.refresh());
        }

        setTimeout(() => ScrollTrigger.refresh(), 150);
    }, el);
});

onUnmounted(() => {
    ctx?.revert();
});
</script>

<template>
    <section id="capabilities" class="capabilities-section" ref="sectionRef">
        <div class="cap-viewport">
            <!-- Header (fades out on scroll) -->
            <div class="cap-header">
                <span class="section-eyebrow">Agency Capability</span>
                <h2 class="section-title">
                    Built on capability, governed by integrity
                </h2>
                <p class="cap-subtitle">
                    Eight pillars that define how we operate, recruit, train,
                    and protect.
                </p>
            </div>

            <!-- Animated display -->
            <Transition :name="`zoom-${animDir}`" mode="out-in">
                <div :key="currentIndex" class="cap-display">
                    <!-- Left: icon, number, title -->
                    <div class="cap-left">
                        <div class="cap-icon-wrap">
                            <component
                                :is="capabilities[currentIndex]!.icon"
                                :size="32"
                            />
                        </div>
                        <span class="cap-number">{{
                            String(currentIndex + 1).padStart(2, '0')
                        }}</span>
                        <h3 class="cap-title">
                            {{ capabilities[currentIndex]!.title }}
                        </h3>
                    </div>

                    <!-- Right: description with accent border -->
                    <div class="cap-right">
                        <p class="cap-text">
                            {{ capabilities[currentIndex]!.text }}
                        </p>
                    </div>
                </div>
            </Transition>

            <!-- Dots -->
            <div class="cap-dots">
                <span
                    v-for="(_, i) in capabilities"
                    :key="i"
                    class="cap-dot"
                ></span>
            </div>
        </div>
    </section>
</template>

<style scoped>
.capabilities-section {
    width: 100%;
    height: 100vh;
    background: #fff;
    overflow: hidden;
}

.cap-viewport {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 1400px;
    height: 100vh;
    margin: 0 auto;
    padding: 1.5rem;
    position: relative;
}

/* Header — always visible */
.cap-header {
    text-align: center;
    margin-bottom: clamp(1.5rem, 4vh, 3.5rem);
}

.cap-header .section-title {
    font-size: clamp(1.5rem, 3.5vw, 2.75rem);
}

.cap-subtitle {
    color: var(--color-text-muted);
    font-size: clamp(0.9rem, 1.2vw, 1.1rem);
    margin-top: 0.5rem;
}

/* Display grid */
.cap-display {
    display: grid;
    grid-template-columns: 1fr;
    align-items: center;
    gap: 1.5rem;
    width: 100%;
    max-width: 1100px;
}

/* Left column */
.cap-left {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.cap-icon-wrap {
    background: var(--color-primary);
    color: #fff;
    border-radius: clamp(12px, 1.5vw, 20px);
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: clamp(52px, 6vw, 80px);
    height: clamp(52px, 6vw, 80px);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.cap-number {
    font-family: var(--font-display);
    color: rgba(74, 95, 128, 0.1);
    font-size: clamp(2rem, 4vw, 4rem);
    font-weight: 800;
    line-height: 1;
}

.cap-title {
    font-family: var(--font-sans);
    color: var(--color-primary-dark);
    letter-spacing: -0.02em;
    font-size: clamp(1.25rem, 2.5vw, 2.5rem);
    font-weight: 800;
}

/* Right column */
.cap-right {
    border-left: 3px solid var(--color-accent);
    display: flex;
    align-items: center;
    min-height: 80px;
    padding: 1.25rem;
}

.cap-text {
    color: var(--color-text-muted);
    font-size: clamp(0.95rem, 1.3vw, 1.25rem);
    line-height: 1.8;
}

/* Dots */
.cap-dots {
    display: flex;
    gap: 0.5rem;
    position: absolute;
    bottom: clamp(1rem, 3vh, 2.5rem);
    left: 50%;
    transform: translateX(-50%);
}

.cap-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--color-border);
    transition:
        background-color 0.3s ease,
        transform 0.3s ease;
}

.cap-dot.visited {
    background: var(--color-primary-soft);
}

.cap-dot.active {
    background: var(--color-accent);
    transform: scale(1.4);
}

/* Scroll-triggered slide transitions (GSAP scroll moves right→left, left←right) */
.zoom-right-enter-active,
.zoom-right-leave-active,
.zoom-left-enter-active,
.zoom-left-leave-active,
.zoom-center-enter-active,
.zoom-center-leave-active {
    transition:
        opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Scroll down → new card enters from right */
.zoom-right-enter-from {
    opacity: 0;
    transform: scale(0.85) translateX(40px);
}

.zoom-right-leave-to {
    opacity: 0;
    transform: scale(0.85) translateX(-40px);
}

/* Scroll up → new card enters from left */
.zoom-left-enter-from {
    opacity: 0;
    transform: scale(0.85) translateX(-40px);
}

.zoom-left-leave-to {
    opacity: 0;
    transform: scale(0.85) translateX(40px);
}

.zoom-center-enter-from,
.zoom-center-leave-to {
    opacity: 0;
    transform: scale(0.85);
}

/* Responsive */
@media (min-width: 768px) {
    .cap-viewport {
        padding: 2rem 2.5rem;
    }

    .cap-display {
        grid-template-columns: 1fr 1.4fr;
        gap: 3rem;
    }

    .cap-right {
        padding: 1.5rem 2rem;
    }
}

@media (min-width: 1200px) {
    .cap-viewport {
        padding: 3rem 4rem;
    }

    .cap-display {
        grid-template-columns: 1fr 1.5fr;
        gap: 5rem;
    }

    .cap-right {
        min-height: 140px;
        padding: 2rem 2.5rem;
    }
}

@media (min-width: 1600px) {
    .cap-viewport {
        max-width: 1600px;
        padding: 4rem 6rem;
    }

    .cap-display {
        gap: 6rem;
        max-width: 1300px;
    }

    .cap-icon-wrap {
        border-radius: 22px;
        width: 90px;
        height: 90px;
    }

    .cap-right {
        min-height: 160px;
        padding: 2.5rem 3rem;
    }
}
</style>
