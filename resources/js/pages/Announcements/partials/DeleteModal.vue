<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { destroy } from '@/routes/announcements';
import BaseModal from '@/components/BaseModal.vue';
import { Button } from '@/components/ui/button';

type Announcement = {
    id: number;
    title: string;
};

type Props = {
    announcement: Announcement;
};

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('open');

const form = useForm({});

function handleDelete() {
    form.delete(destroy(props.announcement.id).url, {
        onSuccess: () => {
            isOpen.value = false;
        },
    });
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Delete Announcement"
        :description="`Are you sure you want to delete &quot;${props.announcement.title}&quot;? This action cannot be undone.`"
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
