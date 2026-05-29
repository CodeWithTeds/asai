<script setup lang="ts">
import { Menu, X } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

const scrolled = ref(false);
const mobileOpen = ref(false);

const navLinks = [
    { href: '#about', label: 'About' },
    { href: '#vision', label: 'Vision & Mission' },
    { href: '#capabilities', label: 'Capabilities' },
    { href: '#services', label: 'Services' },
    { href: '#contact', label: 'Contact' },
];

function handleNavClick(e: Event, href: string) {
    e.preventDefault();
    closeMobile();

    const target = document.querySelector(href);

    if (!target) {
        return;
    }

    const headerOffset = 64;
    const elementPosition = target.getBoundingClientRect().top + window.scrollY;
    window.scrollTo({
        top: elementPosition - headerOffset,
        behavior: 'instant',
    });
}

function onScroll() {
    scrolled.value = window.scrollY > 12;
}

function closeMobile() {
    mobileOpen.value = false;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <header class="site-header" :class="{ scrolled }">
        <div class="header-inner container">
            <a href="#top" class="brand" @click="closeMobile">
                <img
                    src="/images/logo.png"
                    alt="ASAI Logo"
                    class="brand-logo"
                />
                <span class="brand-name">ASAI</span>
            </a>

            <nav class="nav-desktop" aria-label="Primary">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="nav-link"
                    @click="handleNavClick($event, link.href)"
                >
                    {{ link.label }}
                </a>
            </nav>

            <a href="#contact" class="cta-desktop">Get in touch</a>

            <button
                class="menu-toggle"
                :aria-expanded="mobileOpen"
                aria-label="Toggle navigation"
                @click="mobileOpen = !mobileOpen"
            >
                <component :is="mobileOpen ? X : Menu" :size="22" />
            </button>
        </div>

        <Transition name="slide">
            <nav v-if="mobileOpen" class="nav-mobile" aria-label="Mobile">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="nav-link-mobile"
                    @click="handleNavClick($event, link.href)"
                >
                    {{ link.label }}
                </a>
                <a href="#contact" class="cta-mobile" @click="closeMobile"
                    >Get in touch</a
                >
            </nav>
        </Transition>
    </header>
</template>

<style scoped>
.site-header {
    position: sticky;
    top: 0;
    z-index: 50;
    height: var(--header-height);
    background: rgba(242, 246, 246, 0.85);
    backdrop-filter: saturate(140%) blur(14px);
    -webkit-backdrop-filter: saturate(140%) blur(14px);
    border-bottom: 1px solid transparent;
    transition:
        background-color 0.25s ease,
        box-shadow 0.25s ease,
        border-color 0.25s ease;
}

.site-header.scrolled {
    background: rgba(255, 255, 255, 0.92);
    border-bottom-color: var(--color-border);
    box-shadow: 0 4px 20px var(--color-shadow);
}

.header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    gap: 1rem;
}

.brand {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.15rem;
    color: var(--color-primary-dark);
}

.brand-logo {
    height: 36px;
    width: auto;
    display: block;
}

.nav-desktop {
    display: none;
    gap: 1.75rem;
}

.nav-link {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--color-text-muted);
    transition: color 0.2s ease;
    position: relative;
}

.nav-link:hover {
    color: var(--color-primary);
}

.nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -6px;
    height: 2px;
    background: var(--color-accent);
    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.2s ease;
}

.nav-link:hover::after {
    transform: scaleX(1);
}

.cta-desktop {
    display: none;
    background: var(--color-primary);
    color: #fff;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.55rem 1.1rem;
    border-radius: var(--radius-sm);
    transition:
        background-color 0.2s ease,
        transform 0.2s ease;
}

.cta-desktop:hover {
    background: var(--color-primary-dark);
    transform: translateY(-1px);
}

.menu-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    color: var(--color-primary-dark);
    transition: background-color 0.2s ease;
}

.menu-toggle:hover {
    background: rgba(74, 95, 128, 0.08);
}

.nav-mobile {
    position: absolute;
    top: var(--header-height);
    left: 0;
    right: 0;
    background: #fff;
    border-bottom: 1px solid var(--color-border);
    padding: 1rem 1.5rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    box-shadow: 0 8px 24px var(--color-shadow);
}

.nav-link-mobile {
    padding: 0.75rem 0.25rem;
    font-size: 0.95rem;
    color: var(--color-text);
    border-bottom: 1px solid var(--color-border);
}

.nav-link-mobile:last-of-type {
    border-bottom: none;
}

.cta-mobile {
    margin-top: 0.75rem;
    text-align: center;
    background: var(--color-primary);
    color: #fff;
    font-weight: 600;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-sm);
}

.slide-enter-active,
.slide-leave-active {
    transition:
        opacity 0.2s ease,
        transform 0.2s ease;
}
.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

@media (min-width: 768px) {
    .nav-desktop {
        display: flex;
    }
    .cta-desktop {
        display: inline-flex;
        align-items: center;
    }
    .menu-toggle {
        display: none;
    }
}
</style>
