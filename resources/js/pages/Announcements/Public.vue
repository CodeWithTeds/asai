<script setup lang="ts">
import '../../../css/welcome/main.css';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import {
    Megaphone,
    Search,
    ChevronRight,
    Home,
    Mail,
    CheckCircle2,
    ChevronDown,
    ChevronUp,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import AppFooter from '@/components/Welcome/AppFooter.vue';
import AppHeader from '@/components/Welcome/AppHeader.vue';

type Announcement = {
    id: number;
    title: string;
    body: string;
    type: string;
    image: string | null;
    starts_at: string | null;
    expires_at: string | null;
    created_at: string;
};

// Retrieve globally shared announcements
const announcements = computed<Announcement[]>(
    () => (usePage().props.announcements as Announcement[]) ?? [],
);

// State
const searchQuery = ref(
    new URLSearchParams(window.location.search).get('search') || '',
);
const selectedType = ref(
    new URLSearchParams(window.location.search).get('type') || 'all',
);

const types = [
    { value: 'all', label: 'All' },
    { value: 'event', label: 'Events' },
    { value: 'activity', label: 'Activities' },
    { value: 'news', label: 'News' },
    { value: 'promos', label: 'Promos' },
];

function formatTypeLabel(type: string) {
    const labels: Record<string, string> = {
        event: 'Event',
        activity: 'Activity',
        news: 'News',
        promos: 'Promos',
    };

    return labels[type] ?? type;
}
const expandedId = ref<number | null>(null);

let searchTimeout: any = null;
watch([searchQuery, selectedType], ([newQuery, newType]) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            '/news',
            {
                search: newQuery || undefined,
                type: newType !== 'all' ? newType : undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);
});

// Subscription form state
const email = ref('');
const isSubscribed = ref(false);
const subscribeError = ref('');

// Format date matching the mockup
function formatAnnouncementDate(dateStr: string) {
    if (!dateStr) {
        return '';
    }

    const d = new Date(dateStr);
    const today = new Date();

    if (d.toDateString() === today.toDateString()) {
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        return `Today ${hours}:${minutes}`;
    }

    return d.toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
    });
}

// Filtered announcements list
const filteredAnnouncements = computed(() => announcements.value);

// Toggle expand announcement
function toggleExpand(id: number) {
    if (expandedId.value === id) {
        expandedId.value = null;
    } else {
        expandedId.value = id;
    }
}

// Handle newsletter subscription
function handleSubscribe() {
    subscribeError.value = '';

    if (!email.value || !email.value.includes('@')) {
        subscribeError.value = 'Please enter a valid email address.';

        return;
    }

    isSubscribed.value = true;
    email.value = '';
}
</script>

<template>
    <Head>
        <title>Announcements & Latest News | ASAI</title>
        <meta
            name="description"
            content="Stay updated with the latest news, announcements, activities, and events from Annapolis Security Agency, Inc. (ASAI)."
        />
    </Head>

    <div class="announcements-page-wrapper">
        <!-- Main Site Header -->
        <AppHeader />

        <!-- Header Hero Section -->
        <section class="hero-banner">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="megaphone-icon-wrapper">
                        <Megaphone class="megaphone-icon" />
                    </div>
                    <div class="title-search-wrapper">
                        <h1 class="hero-title">Announcements</h1>
                        <div class="search-input-group">
                            <input
                                id="announcements-search"
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search articles"
                                class="search-field"
                            />
                            <Search class="search-icon" :size="18" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Area -->
        <main class="main-content container">
            <!-- Breadcrumbs -->
            <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                <Link
                    href="/"
                    class="breadcrumb-link"
                    id="breadcrumb-home-link"
                >
                    <Home :size="14" />
                    <span>Home</span>
                </Link>
                <ChevronRight :size="12" class="breadcrumb-separator" />
                <span class="breadcrumb-active">Announcements</span>
            </nav>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button
                    v-for="type in types"
                    :key="type.value"
                    :class="[
                        'filter-tab',
                        { active: selectedType === type.value },
                    ]"
                    @click="selectedType = type.value"
                >
                    {{ type.label }}
                </button>
            </div>

            <!-- Announcements List -->
            <div class="announcements-container">
                <TransitionGroup
                    name="list-anim"
                    tag="div"
                    class="announcements-list"
                    v-if="filteredAnnouncements.length > 0"
                >
                    <article
                        v-for="item in filteredAnnouncements"
                        :key="item.id"
                        class="announcement-item"
                        :class="{ expanded: expandedId === item.id }"
                    >
                        <div
                            :id="`announcement-row-${item.id}`"
                            class="announcement-row"
                            @click="toggleExpand(item.id)"
                        >
                            <!-- Left Column: Date & Time -->
                            <div class="announcement-date-col">
                                <span class="announcement-date">{{
                                    formatAnnouncementDate(
                                        item.created_at || item.starts_at,
                                    )
                                }}</span>
                                <span class="type-badge" :class="item.type">
                                    {{ formatTypeLabel(item.type) }}
                                </span>
                            </div>

                            <!-- Right Column: Content Intro -->
                            <div class="announcement-content-col">
                                <h3 class="announcement-title">
                                    {{ item.title }}
                                </h3>
                                <p
                                    class="announcement-snippet"
                                    v-if="expandedId !== item.id"
                                >
                                    {{ item.body }}
                                </p>
                                <button
                                    :id="`announcement-toggle-btn-${item.id}`"
                                    class="toggle-indicator-btn"
                                    aria-label="Read more"
                                >
                                    <span class="btn-text">{{
                                        expandedId === item.id
                                            ? 'Show less'
                                            : 'Read more'
                                    }}</span>
                                    <component
                                        :is="
                                            expandedId === item.id
                                                ? ChevronUp
                                                : ChevronDown
                                        "
                                        :size="16"
                                    />
                                </button>
                            </div>
                        </div>

                        <!-- Expandable full body text -->
                        <div
                            class="announcement-body-expanded"
                            v-if="expandedId === item.id"
                        >
                            <div class="body-inner">
                                <div
                                    v-if="item.image"
                                    class="announcement-image-wrapper"
                                >
                                    <img
                                        :src="`/storage/${item.image}`"
                                        :alt="item.title"
                                        class="announcement-image"
                                    />
                                </div>
                                <p
                                    v-for="(paragraph, pIdx) in item.body.split(
                                        '\n',
                                    )"
                                    :key="pIdx"
                                    class="body-paragraph"
                                >
                                    {{ paragraph }}
                                </p>
                            </div>
                        </div>
                    </article>
                </TransitionGroup>

                <!-- Empty State -->
                <div v-else class="empty-state">
                    <div class="empty-icon-box">
                        <Search :size="32" />
                    </div>
                    <h3 class="empty-title">No Announcements Found</h3>
                    <p class="empty-text" v-if="searchQuery">
                        We couldn't find any announcements matching "{{
                            searchQuery
                        }}".
                    </p>
                    <p class="empty-text" v-else>
                        We don't have any announcements right now. Check back
                        later!
                    </p>
                    <button
                        v-if="searchQuery"
                        id="clear-search-btn"
                        class="clear-search-btn"
                        @click="searchQuery = ''"
                    >
                        Clear Search
                    </button>
                </div>
            </div>
        </main>

        <!-- Newsletter Subscription Section -->
        <section class="newsletter-section">
            <div class="newsletter-container container">
                <div class="newsletter-card">
                    <h2 class="newsletter-title">
                        Get All The Latest News And Updates From ASAI!
                    </h2>

                    <div
                        v-if="isSubscribed"
                        class="success-message"
                        id="newsletter-success-msg"
                    >
                        <CheckCircle2 :size="24" class="success-icon" />
                        <span
                            >Thank you for subscribing to our news
                            updates!</span
                        >
                    </div>

                    <form
                        v-else
                        @submit.prevent="handleSubscribe"
                        class="subscription-form"
                        id="newsletter-form"
                    >
                        <div class="input-wrapper">
                            <Mail class="input-icon" :size="18" />
                            <input
                                id="newsletter-email-input"
                                v-model="email"
                                type="email"
                                placeholder="Enter your email address"
                                class="email-input"
                                required
                            />
                        </div>
                        <button
                            type="submit"
                            id="newsletter-subscribe-btn"
                            class="subscribe-btn"
                        >
                            Subscribe
                        </button>
                    </form>
                    <p
                        v-if="subscribeError"
                        id="newsletter-error-msg"
                        class="error-message"
                    >
                        {{ subscribeError }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Site Footer -->
        <AppFooter />
    </div>
</template>

<style scoped>
.announcements-page-wrapper {
    background: var(--color-bg);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Hero banner */
.hero-banner {
    position: relative;
    background: linear-gradient(135deg, #f8fafa 0%, #edf3f3 100%);
    padding: 3.5rem 0 3.5rem;
    overflow: hidden;
    border-bottom: 1px solid var(--color-border);
}

.hero-container {
    max-width: var(--container-max);
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 2;
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 2.5rem;
}

.megaphone-icon-wrapper {
    background: rgba(29, 33, 157, 0.07);
    border: 1px solid rgba(29, 33, 157, 0.12);
    padding: 1.5rem;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary);
    box-shadow: 0 10px 30px rgba(29, 33, 157, 0.04);
}

.megaphone-icon {
    width: 48px;
    height: 48px;
    transform: rotate(-10deg);
}

.title-search-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
    flex: 1;
}

.hero-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--color-primary-dark);
    margin: 0;
    letter-spacing: -0.01em;
}

.search-input-group {
    position: relative;
    width: 100%;
    max-width: 440px;
}

.search-field {
    width: 100%;
    padding: 0.75rem 2.75rem 0.75rem 1.25rem;
    border-radius: 12px;
    border: 1px solid rgba(74, 95, 128, 0.2);
    font-size: 0.95rem;
    background: #ffffff;
    color: var(--color-text);
    outline: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: all 0.25s ease;
}

.search-field:focus {
    border-color: var(--color-primary);
    box-shadow:
        0 0 0 4px rgba(29, 33, 157, 0.1),
        0 4px 12px rgba(0, 0, 0, 0.05);
}

.search-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-muted);
    pointer-events: none;
}

/* Breadcrumbs */
.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem 0;
    font-size: 0.8rem;
    color: var(--color-text-muted);
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--color-text-muted);
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: var(--color-primary);
}

.breadcrumb-separator {
    color: rgba(74, 95, 128, 0.3);
}

.breadcrumb-active {
    font-weight: 600;
    color: var(--color-primary-dark);
}

/* Main Content */
.main-content {
    flex: 1;
    margin-bottom: 4rem;
}

/* Announcements List */
.announcements-container {
    position: relative;
}

.announcements-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.announcement-item {
    background: var(--color-bg-elevated);
    border-bottom: 1px solid var(--color-border);
    transition: all 0.25s ease;
}

.announcement-item:first-of-type {
    border-top: 1px solid var(--color-border);
}

.announcement-row {
    display: flex;
    padding: 1.75rem 1rem;
    cursor: pointer;
    gap: 2rem;
    transition: background-color 0.2s ease;
}

.announcement-row:hover {
    background-color: rgba(29, 33, 157, 0.02);
}

/* Left Col: Date styling */
.announcement-date-col {
    width: 140px;
    flex-shrink: 0;
}

.announcement-date {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--color-text-muted);
    letter-spacing: -0.01em;
    display: block;
}

/* Right Col: Content */
.announcement-content-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.announcement-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    margin: 0;
    line-height: 1.35;
    transition: color 0.2s ease;
}

.announcement-row:hover .announcement-title {
    color: var(--color-primary-soft);
}

.announcement-snippet {
    font-size: 0.88rem;
    color: var(--color-text-muted);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.toggle-indicator-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-top: 0.25rem;
    align-self: flex-start;
    padding: 0;
}

.toggle-indicator-btn:hover {
    color: var(--color-primary-soft);
}

/* Expanded body details */
.announcement-body-expanded {
    background: rgba(29, 33, 157, 0.01);
    border-top: 1px dashed rgba(74, 95, 128, 0.1);
    padding: 0 1rem 1.75rem 11.75rem;
    /* Indents body content to line up with title */
}

.body-inner {
    max-width: 780px;
}

.body-paragraph {
    font-size: 0.92rem;
    color: var(--color-text);
    line-height: 1.7;
    margin-bottom: 0.75rem;
}

.body-paragraph:last-of-type {
    margin-bottom: 0;
}

/* Empty State */
.empty-state {
    padding: 6rem 2rem;
    text-align: center;
    max-width: 500px;
    margin: 0 auto;
}

.empty-icon-box {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(74, 95, 128, 0.08);
    color: var(--color-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    margin-bottom: 0.5rem;
}

.empty-text {
    font-size: 0.9rem;
    color: var(--color-text-muted);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.clear-search-btn {
    background: var(--color-primary);
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    transition: background 0.2s ease;
}

.clear-search-btn:hover {
    background: var(--color-primary-soft);
}

/* Newsletter section */
.newsletter-section {
    background: #ffffff;
    border-top: 1px solid var(--color-border);
    padding: 4rem 0;
}

.newsletter-container {
    display: flex;
    justify-content: center;
}

.newsletter-card {
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.newsletter-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--color-primary-dark);
    line-height: 1.4;
    margin-bottom: 1.5rem;
}

.subscription-form {
    display: flex;
    gap: 0.75rem;
    max-width: 480px;
    margin: 0 auto;
}

.input-wrapper {
    position: relative;
    flex: 1;
}

.input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-muted);
}

.email-input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.75rem;
    border: 1px solid rgba(74, 95, 128, 0.2);
    border-radius: 12px;
    font-size: 0.95rem;
    outline: none;
    transition: all 0.25s ease;
}

.email-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(29, 33, 157, 0.08);
}

.subscribe-btn {
    background: var(--color-primary);
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 700;
    padding: 0 1.75rem;
    border-radius: 12px;
    transition:
        background 0.2s ease,
        transform 0.15s ease;
    white-space: nowrap;
}

.subscribe-btn:hover {
    background: var(--color-primary-soft);
}

.subscribe-btn:active {
    transform: scale(0.97);
}

.success-message {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    color: #059669;
    background: #ecfdf5;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-size: 0.92rem;
    font-weight: 600;
    border: 1px solid #a7f3d0;
}

.success-icon {
    color: #10b981;
}

.error-message {
    font-size: 0.82rem;
    color: #dc2626;
    margin-top: 0.5rem;
}

/* Animations */
.list-anim-enter-active,
.list-anim-leave-active {
    transition: all 0.4s ease;
}

.list-anim-enter-from,
.list-anim-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

/* Responsive adjustment */
@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
        gap: 1.25rem;
    }

    .search-input-group {
        margin: 0 auto;
    }

    .title-search-wrapper {
        align-items: center;
    }

    .announcement-row {
        flex-direction: column;
        gap: 0.75rem;
        padding: 1.5rem 0.5rem;
    }

    .announcement-date-col {
        width: auto;
    }

    .announcement-body-expanded {
        padding: 0 0.5rem 1.5rem;
    }

    .subscription-form {
        flex-direction: column;
    }

    .subscribe-btn {
        padding: 0.8rem;
    }
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.75rem;
}

.filter-tab {
    padding: 0.5rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-text-muted);
    border-radius: 9999px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    background: transparent;
    cursor: pointer;
}

.filter-tab:hover {
    color: var(--color-primary);
    background: rgba(29, 33, 157, 0.04);
}

.filter-tab.active {
    color: var(--color-primary);
    background: rgba(29, 33, 157, 0.08);
    border-color: rgba(29, 33, 157, 0.15);
}

/* Type Badge */
.type-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 9999px;
    padding: 0.125rem 0.625rem;
    font-size: 0.72rem;
    font-weight: 700;
    margin-top: 0.5rem;
    text-transform: capitalize;
    border: 1px solid transparent;
}

.type-badge.event {
    background-color: #eff6ff;
    border-color: #dbeafe;
    color: #1d4ed8;
}

.type-badge.activity {
    background-color: #ecfdf5;
    border-color: #d1fae5;
    color: #047857;
}

.type-badge.news {
    background-color: #f0fdfa;
    border-color: #ccfbf1;
    color: #0f766e;
}

.type-badge.promos {
    background-color: #fdf2f8;
    border-color: #fbcfe8;
    color: #be185d;
}

/* Announcement Image */
.announcement-image-wrapper {
    margin-bottom: 1.25rem;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--color-border);
    max-height: 400px;
    background-color: rgba(0, 0, 0, 0.01);
}

.announcement-image {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    display: block;
}
</style>
