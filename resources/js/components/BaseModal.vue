<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

type Props = {
    title: string;
    description?: string;
    size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl';
};

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
});

const isOpen = defineModel<boolean>('open');

const sizeClass: Record<string, string> = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '3xl': 'sm:max-w-3xl',
    '4xl': 'sm:max-w-4xl',
    '5xl': 'sm:max-w-5xl',
};

const heightClass: Record<string, string> = {
    sm: 'sm:min-h-[160px]',
    md: 'sm:min-h-[260px]',
    lg: 'sm:min-h-[360px]',
    xl: 'sm:min-h-[420px]',
    '2xl': 'sm:min-h-[480px]',
    '3xl': 'sm:min-h-[520px]',
    '4xl': 'sm:min-h-[580px]',
    '5xl': 'sm:min-h-[640px]',
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent
            :class="[
                sizeClass[props.size],
                heightClass[props.size],
                'flex max-h-[90vh] flex-col gap-0 overflow-hidden',
            ]"
        >
            <!-- Modal header -->
            <DialogHeader class="mb-4 flex-shrink-0">
                <DialogTitle>{{ props.title }}</DialogTitle>
                <DialogDescription v-if="props.description" class="mt-1">
                    {{ props.description }}
                </DialogDescription>
            </DialogHeader>

            <!-- Modal body slot -->
            <div
                v-if="$slots.default"
                class="-mx-2 flex-1 overflow-y-auto px-2"
            >
                <slot />
            </div>

            <!-- Optional footer slot -->
            <div v-if="$slots.footer" class="mt-auto flex-shrink-0 pt-4">
                <slot name="footer" />
            </div>
        </DialogContent>
    </Dialog>
</template>
