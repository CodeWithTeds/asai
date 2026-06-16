<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Phone, Menu, X } from 'lucide-vue-next';
import { ref } from 'vue';

const mobileOpen = ref(false);

const navLinks = [
    { href: '/#about', label: 'About Us', isAnchor: true },
    { href: '/#vision', label: 'Vision & Mission', isAnchor: true },
    { href: '/#capabilities', label: 'Capabilities', isAnchor: true },
    { href: '/#services', label: 'Services', isAnchor: true },
    { href: '/#clients', label: 'Clients', isAnchor: true },
    { href: '/careers', label: 'Careers', isAnchor: false },
    { href: '/news', label: 'Announcements', isAnchor: false },
];

function handleNavClick(
    e: Event,
    link: { href: string; label: string; isAnchor: boolean },
) {
    if (!link.isAnchor) {
        mobileOpen.value = false;

        return;
    }

    const page = usePage();

    if (page.url === '/' || page.url.startsWith('/#') || page.url === '') {
        e.preventDefault();
        mobileOpen.value = false;
        const hash = link.href.substring(1); // remove the leading '/'
        const target = document.querySelector(hash);

        if (!target) {
            return;
        }

        const elementPosition =
            target.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({ top: elementPosition - 72, behavior: 'instant' });
    } else {
        mobileOpen.value = false;
    }
}

function handleLogoClick(e: Event) {
    const page = usePage();

    if (page.url === '/' || page.url.startsWith('/#') || page.url === '') {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'instant' });
    }
}
</script>

<template>
    <header class="site-header">
        <!-- Logo zone with angled divider -->
        <Link href="/" class="brand-zone" @click="handleLogoClick">
            <img src="/images/logo.png" alt="ASAI Logo" class="brand-logo" />
        </Link>

        <!-- Nav + CTA -->
        <nav class="nav-desktop" aria-label="Primary">
            <template v-for="(link, i) in navLinks" :key="link.href">
                <Link
                    v-if="!link.isAnchor"
                    :href="link.href"
                    class="nav-link"
                    :class="{ active: $page.url === link.href }"
                    >{{ link.label }}</Link
                >
                <a
                    v-else
                    :href="link.href"
                    class="nav-link"
                    @click="handleNavClick($event, link)"
                    >{{ link.label }}</a
                >
                <span v-if="i < navLinks.length - 1" class="nav-sep">|</span>
            </template>
        </nav>

        <a
            href="/#contact"
            class="cta-btn"
            @click="
                handleNavClick($event, {
                    href: '/#contact',
                    label: 'Contact',
                    isAnchor: true,
                })
            "
        >
            <Phone :size="14" />
            Contact Us
        </a>

        <!-- Mobile toggle -->
        <button
            class="menu-toggle"
            :aria-expanded="mobileOpen"
            aria-label="Toggle navigation"
            @click="mobileOpen = !mobileOpen"
        >
            <component :is="mobileOpen ? X : Menu" :size="22" />
        </button>

        <!-- Mobile menu -->
        <Transition name="slide">
            <nav v-if="mobileOpen" class="nav-mobile" aria-label="Mobile">
                <template v-for="link in navLinks" :key="link.href">
                    <Link
                        v-if="!link.isAnchor"
                        :href="link.href"
                        class="nav-link-mobile"
                        @click="mobileOpen = false"
                        >{{ link.label }}</Link
                    >
                    <a
                        v-else
                        :href="link.href"
                        class="nav-link-mobile"
                        @click="handleNavClick($event, link)"
                        >{{ link.label }}</a
                    >
                </template>
                <a
                    href="/#contact"
                    class="cta-mobile"
                    @click="
                        handleNavClick($event, {
                            href: '/#contact',
                            label: 'Contact',
                            isAnchor: true,
                        })
                    "
                >
                    <Phone :size="14" /> Contact Us
                </a>
            </nav>
        </Transition>
    </header>
</template>

<style scoped>
.site-header {
    position: sticky;
    top: 0;
    z-index: 50;
    height: 100px;
    background: #0d1b4b;
    display: flex;
    align-items: center;
    gap: 0;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.4);
    overflow: visible;
}

/* Logo zone - white bg with angled right edge */
.brand-zone {
    display: flex;
    align-items: center;
    height: 100%;
    background: #fff;
    padding: 0 3rem 0 1.5rem;
    clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 100%, 0 100%);
    text-decoration: none;
    flex-shrink: 0;
    overflow: visible;
    position: relative;
    z-index: 2;
}

.brand-logo {
    height: 140px;
    width: auto;
    display: block;
    position: relative;
    top: 10px;
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
}

.brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 900;
    color: #0d1b4b;
    letter-spacing: 0.05em;
}

.brand-sub {
    font-size: 0.65rem;
    font-weight: 500;
    color: #555;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

/* Desktop nav */
.nav-desktop {
    display: none;
    align-items: center;
    gap: 0.5rem;
    margin-left: auto;
    padding: 0 2rem;
}

.nav-link {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.2s ease;
    white-space: nowrap;
    padding: 0 0.25rem;
}

.nav-link:hover,
.nav-link.active {
    color: #c9a84c;
}

.nav-sep {
    color: rgba(255, 255, 255, 0.25);
    font-size: 0.75rem;
    user-select: none;
}

/* CTA button */
.cta-btn {
    display: none;
    align-items: center;
    gap: 0.4rem;
    background: #c9a84c;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.7rem 1.25rem;
    border-radius: 4px;
    margin-right: 1.5rem;
    white-space: nowrap;
    text-decoration: none;
    transition:
        background 0.2s ease,
        transform 0.2s ease;
    flex-shrink: 0;
}

.cta-btn:hover {
    background: #b8940e;
    transform: translateY(-1px);
}

/* Mobile toggle */
.menu-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 4px;
    color: #fff;
    margin-left: auto;
    margin-right: 1rem;
    background: none;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.menu-toggle:hover {
    background: rgba(255, 255, 255, 0.08);
}

/* Mobile nav */
.nav-mobile {
    position: absolute;
    top: 100px;
    left: 0;
    right: 0;
    background: #0d1b4b;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    z-index: 100;
}

.nav-link-mobile {
    padding: 0.8rem 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.8);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    text-decoration: none;
}

.cta-mobile {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    background: #c9a84c;
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.85rem 1rem;
    border-radius: 4px;
    text-decoration: none;
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

@media (min-width: 900px) {
    .nav-desktop {
        display: flex;
    }

    .cta-btn {
        display: flex;
    }

    .menu-toggle {
        display: none;
    }
}
</style>
