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
    size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
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
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent :class="sizeClass[props.size]">
            <!-- Modal header -->
            <DialogHeader>
                <DialogTitle>{{ props.title }}</DialogTitle>
                <DialogDescription v-if="props.description">
                    {{ props.description }}
                </DialogDescription>
            </DialogHeader>

            <!-- Modal body slot -->
            <slot />

            <!-- Optional footer slot -->
            <slot name="footer" />
        </DialogContent>
    </Dialog>
</template>
