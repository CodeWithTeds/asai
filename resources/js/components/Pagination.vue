<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Props = {
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
    links: PaginationLink[];
};

const props = defineProps<Props>();

const pageLinks = computed(() => props.links.slice(1, -1));
const prevLink = computed(() => props.links[0]);
const nextLink = computed(() => props.links[props.links.length - 1]);
</script>

<template>
    <div class="flex items-center justify-between py-4">
        <p class="text-sm text-muted-foreground">
            Showing
            <span class="font-medium text-foreground">{{ perPage }}</span>
            of
            <span class="font-medium text-foreground">{{ total }}</span>
            entries
        </p>

        <nav class="flex items-center gap-0.5" aria-label="Pagination">
            <Link
                v-if="prevLink?.url"
                :href="prevLink.url"
                preserve-state
                preserve-scroll
                class="px-3 py-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground"
            >
                Previous
            </Link>
            <span
                v-else
                class="px-3 py-1.5 text-sm text-muted-foreground opacity-40"
            >
                Previous
            </span>

            <template v-for="link in pageLinks" :key="link.label">
                <Link
                    v-if="link.url && !link.active"
                    :href="link.url"
                    preserve-state
                    preserve-scroll
                    class="min-w-[32px] rounded-lg px-2.5 py-1.5 text-center text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                >
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <span v-html="link.label" />
                </Link>
                <span
                    v-else
                    :class="[
                        'min-w-[32px] rounded-lg px-2.5 py-1.5 text-center text-sm font-medium',
                        link.active
                            ? 'bg-primary text-primary-foreground'
                            : 'text-muted-foreground opacity-40',
                    ]"
                >
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <span v-html="link.label" />
                </span>
            </template>

            <Link
                v-if="nextLink?.url"
                :href="nextLink.url"
                preserve-state
                preserve-scroll
                class="px-3 py-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground"
            >
                Next
            </Link>
            <span
                v-else
                class="px-3 py-1.5 text-sm text-muted-foreground opacity-40"
            >
                Next
            </span>
        </nav>
    </div>
</template>
