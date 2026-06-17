<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-[#f2f6f6] p-6 md:p-10 dark:bg-zinc-950"
    >
        <div class="w-full max-w-[450px] space-y-6">
            <!-- Card Container -->
            <div
                class="rounded-2xl border border-gray-200/60 bg-white p-6 shadow-xl backdrop-blur-sm sm:p-9 dark:border-zinc-800/80 dark:bg-zinc-900"
            >
                <!-- Logo -->
                <div class="mb-3 flex justify-center">
                    <Link
                        :href="home()"
                        class="transition-transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <AppLogo
                            class="h-28 w-full max-w-[240px] object-contain"
                        />
                    </Link>
                </div>

                <!-- Header -->
                <div class="mb-6 space-y-1.5 text-center">
                    <h1
                        class="font-display text-2xl font-bold tracking-tight text-[#0d1b4b] dark:text-white"
                        v-if="title"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="text-sm text-slate-500 dark:text-slate-400"
                        v-if="description"
                    >
                        {{ description }}
                    </p>
                </div>

                <!-- Slot -->
                <slot />
            </div>

            <!-- Footer links -->
            <div
                class="flex justify-center gap-4 text-center text-xs text-slate-400 dark:text-slate-500"
            >
                <span>© 2026 Annapolis Security Agency, Inc.</span>
            </div>
        </div>
    </div>
</template>
