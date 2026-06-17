<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import BaseModal from '@/components/BaseModal.vue';
import { Button } from '@/components/ui/button';
import { destroy } from '@/routes/events/manage';

type Event = {
    id: number;
    title: string;
};

type Props = {
    event: Event;
};

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('open');

const form = useForm({});

function handleDelete() {
    form.delete(destroy(props.event.id).url, {
        onSuccess: () => {
            isOpen.value = false;
        },
    });
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Delete Event"
        :description="`Are you sure you want to delete &quot;${props.event.title}&quot;? This action cannot be undone.`"
        size="sm"
    >
        <!-- Footer -->
        <template #footer>
            <div class="flex justify-end gap-3">
                <Button
                    type="button"
                    variant="outline"
                    :disabled="form.processing"
                    @click="isOpen = false"
                >
                    Cancel
                </Button>
                <Button
                    type="button"
                    variant="destructive"
                    :disabled="form.processing"
                    @click="handleDelete"
                >
                    Delete
                </Button>
            </div>
        </template>
    </BaseModal>
</template>
