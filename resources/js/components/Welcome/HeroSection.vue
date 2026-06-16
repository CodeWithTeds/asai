<script setup lang="ts">
import { ArrowRight, ShieldCheck, Award, Users } from 'lucide-vue-next';
import { onMounted } from 'vue';

declare global {
    interface Window {
        particlesJS: any;
    }
}

onMounted(() => {
    // Trigger hero animations after a short delay for smooth entrance
    setTimeout(() => {
        document.querySelectorAll('.hero-animate').forEach((el, i) => {
            setTimeout(() => el.classList.add('hero-visible'), i * 120);
        });
    }, 100);

    // Initialize particles.js background
    if (window.particlesJS && window.particlesJS.load) {
        window.particlesJS.load('particles-js', '/particles-config.json');
    }
});
</script>

<template>
    <section id="top" class="hero">
        <div id="particles-js" class="particles-bg"></div>
        <div class="hero-grid">
            <div class="hero-content">
                <span class="eyebrow hero-animate"
                    >Annapolis Security Agency, Inc.</span
                >
                <h1 class="hero-title hero-animate">
                    Transforming
                    <span class="accent shimmer-text">security</span> for a
                    better <span class="accent shimmer-text">business.</span>
                </h1>
                <p class="hero-lead hero-animate">
                    A service-oriented corporation putting client needs and
                    satisfaction as our utmost priority — backed by strong
                    financial capabilities and highly skilled security
                    professionals across Luzon, Visayas, and Mindanao.
                </p>

                <div class="hero-cta hero-animate">
                    <a href="#services" class="btn-primary">
                        Explore services
                        <ArrowRight :size="16" />
                    </a>
                    <a href="#about" class="btn-ghost">Learn more</a>
                </div>

                <ul class="hero-stats hero-animate">
                    <li>
                        <Award :size="20" class="stat-icon icon-pulse" />
                        <div>
                            <strong>10+ years</strong>
                            <span>of trusted operations</span>
                        </div>
                    </li>
                    <li>
                        <Users :size="20" class="stat-icon icon-pulse" />
                        <div>
                            <strong>Nationwide</strong>
                            <span>Luzon, Visayas, Mindanao</span>
                        </div>
                    </li>
                    <li>
                        <ShieldCheck :size="20" class="stat-icon icon-pulse" />
                        <div>
                            <strong>SEC Reg.</strong>
                            <span>CS201809381</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="hero-visual hero-animate float-anim">
                <img
                    src="/images/logo.png"
                    alt="ASAI security operations"
                    class="hero-img"
                />
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero {
    min-height: calc(100vh - 100px);
    display: flex;
    align-items: center;
    padding: 2rem 4rem;
    position: relative;
    overflow: hidden;
    background: #0d1117;
}

.particles-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.hero-grid {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.hero-animate {
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero-animate.hero-visible {
    opacity: 1;
    transform: translateY(0);
}

.hero-visual {
    order: 2;
    position: relative;
    display: flex;
    justify-content: flex-end;
}

.hero-img {
    width: 100%;
    max-width: 480px;
    height: auto;
    display: block;
}

.hero-content {
    order: 1;
}

.eyebrow {
    display: inline-block;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-accent);
    margin-bottom: 0.75rem;
}

.hero-title {
    font-size: clamp(1.8rem, 4vw, 3rem);
    margin-bottom: 1rem;
    color: #ffffff;
}

.hero-title .accent {
    font-style: italic;
}

.hero-lead {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    max-width: 560px;
    margin-bottom: 1.5rem;
}

.hero-cta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 2.5rem;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--color-primary);
    color: #fff;
    padding: 0.85rem 1.4rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    transition:
        background-color 0.25s ease,
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

.btn-primary:hover {
    background: var(--color-primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(74, 95, 128, 0.3);
}

.btn-ghost {
    display: inline-flex;
    align-items: center;
    padding: 0.85rem 1.4rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: transparent;
    transition:
        border-color 0.25s ease,
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

.btn-ghost:hover {
    border-color: var(--color-accent);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.2);
}

.hero-stats {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.25rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
}

.hero-stats li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-icon {
    color: var(--color-accent);
    flex-shrink: 0;
}

.hero-stats strong {
    display: block;
    font-weight: 700;
    color: #ffffff;
    font-size: 0.85rem;
}

.hero-stats span {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

@media (min-width: 900px) {
    .hero {
        padding: 2rem 6rem;
    }
    .hero-grid {
        grid-template-columns: 55% 1fr;
        gap: 4rem;
    }
    .hero-content {
        order: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .hero-visual {
        order: 2;
        justify-content: flex-end;
    }
}
</style>
