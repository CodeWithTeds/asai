<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Bell, X, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, ref, onMounted } from 'vue';

type Announcement = {
    id: number;
    title: string;
    body: string;
};

const announcements = computed<Announcement[]>(
    () => (usePage().props.announcements as Announcement[]) ?? [],
);

const currentIndex = ref(0);
const isMounted = ref(false);
const dismissedIds = ref<Set<number>>(new Set());

onMounted(() => {
    if (typeof sessionStorage !== 'undefined') {
        const stored = JSON.parse(
            sessionStorage.getItem('dismissed_announcements') ?? '[]',
        );
        dismissedIds.value = new Set(stored);
    }
    isMounted.value = true;
});

const visible = computed(() =>
    announcements.value.filter((a) => !dismissedIds.value.has(a.id)),
);

const current = computed(() => visible.value[currentIndex.value] ?? null);
const total = computed(() => visible.value.length);

function dismiss() {
    if (!current.value) return;
    dismissedIds.value.add(current.value.id);
    sessionStorage.setItem(
        'dismissed_announcements',
        JSON.stringify([...dismissedIds.value]),
    );
    if (currentIndex.value >= visible.value.length) {
        currentIndex.value = Math.max(0, visible.value.length - 1);
    }
}

function prev() {
    currentIndex.value = (currentIndex.value - 1 + total.value) % total.value;
}

function next() {
    currentIndex.value = (currentIndex.value + 1) % total.value;
}
</script>

<template>
    <Transition name="widget">
        <div v-if="isMounted && current" class="widget">
            <div class="widget-card">
                <!-- Header -->
                <div class="widget-header">
                    <div class="widget-badge">
                        <Bell class="widget-badge-icon" />
                        <span class="widget-badge-label">Announcement</span>
                    </div>
                    <button
                        class="widget-close"
                        @click="dismiss"
                        aria-label="Dismiss announcement"
                    >
                        <X class="widget-close-icon" />
                    </button>
                </div>

                <!-- Body -->
                <div class="widget-body">
                    <div class="widget-title">{{ current.title }}</div>
                    <div class="widget-text">{{ current.body }}</div>
                </div>

                <!-- Footer -->
                <div v-if="total > 1" class="widget-footer">
                    <span class="widget-counter"
                        >{{ currentIndex + 1 }} of {{ total }}</span
                    >
                    <div class="widget-nav">
                        <button
                            class="widget-nav-btn"
                            @click="prev"
                            aria-label="Previous"
                        >
                            <ChevronLeft class="widget-nav-icon" />
                        </button>
                        <button
                            class="widget-nav-btn"
                            @click="next"
                            aria-label="Next"
                        >
                            <ChevronRight class="widget-nav-icon" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 50;
    width: 340px;
}

.widget-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid rgba(74, 95, 128, 0.12);
    box-shadow:
        0 12px 40px -8px rgba(19, 22, 114, 0.12),
        0 4px 12px -4px rgba(74, 95, 128, 0.08);
    overflow: hidden;
}

/* Header */
.widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: linear-gradient(135deg, #131672 0%, #1d219d 100%);
}

.widget-badge {
    display: flex;
    align-items: center;
    gap: 8px;
}

.widget-badge-icon {
    width: 16px;
    height: 16px;
    color: var(--color-accent-soft);
}

.widget-badge-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.9);
}

.widget-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    color: rgba(255, 255, 255, 0.6);
    transition: all 0.15s ease;
}

.widget-close:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
}

.widget-close:active {
    transform: scale(0.9);
}

.widget-close-icon {
    width: 14px;
    height: 14px;
}

/* Body */
.widget-body {
    padding: 18px 20px;
}

.widget-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    line-height: 1.3;
    margin-bottom: 6px;
}

.widget-text {
    font-size: 0.82rem;
    color: var(--color-text-muted);
    line-height: 1.6;
    max-height: 80px;
    overflow-y: auto;
}

.widget-text::-webkit-scrollbar {
    width: 3px;
}

.widget-text::-webkit-scrollbar-thumb {
    background: rgba(74, 95, 128, 0.2);
    border-radius: 9px;
}

/* Footer */
.widget-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 1px solid rgba(74, 95, 128, 0.1);
    background: #f8fafa;
}

.widget-counter {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--color-text-muted);
}

.widget-nav {
    display: flex;
    align-items: center;
    gap: 4px;
}

.widget-nav-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    color: var(--color-text-muted);
    transition: all 0.15s ease;
}

.widget-nav-btn:hover {
    background: var(--color-primary);
    color: #fff;
}

.widget-nav-btn:active {
    transform: scale(0.9);
}

.widget-nav-icon {
    width: 14px;
    height: 14px;
}

/* Transitions */
.widget-enter-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.widget-leave-active {
    transition: all 0.25s ease-in;
}

.widget-enter-from,
.widget-leave-to {
    opacity: 0;
    transform: translateY(16px) scale(0.95);
}

/* Mobile */
@media (max-width: 480px) {
    .widget {
        left: 16px;
        right: 16px;
        bottom: 16px;
        width: auto;
    }
}
</style>
