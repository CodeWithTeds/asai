<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { update } from '@/routes/announcements';
import BaseModal from '@/components/BaseModal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Announcement = {
    id: number;
    title: string;
    body: string;
    status: string;
    starts_at: string | null;
    expires_at: string | null;
};

type Props = {
    announcement: Announcement;
};

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('open');

const form = useForm({
    title: props.announcement.title,
    body: props.announcement.body,
    status: props.announcement.status,
    starts_at: props.announcement.starts_at ?? '',
    expires_at: props.announcement.expires_at ?? '',
});

function handleSubmit() {
    form.put(update(props.announcement.id).url, {
        onSuccess: () => {
            isOpen.value = false;
        },
    });
}

function handleClose() {
    isOpen.value = false;
    form.clearErrors();
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Edit Announcement"
        description="Update the announcement details."
        size="lg"
        @update:open="handleClose"
    >
        <form class="space-y-4" @submit.prevent="handleSubmit">
            <!-- Title -->
            <div class="space-y-1">
                <Label for="edit-title"
                    >Title <span class="text-destructive">*</span></Label
                >
                <Input
                    id="edit-title"
                    v-model="form.title"
                    placeholder="Announcement title"
                    :disabled="form.processing"
                />
                <InputError :message="form.errors.title" />
            </div>

            <!-- Body -->
            <div class="space-y-1">
                <Label for="edit-body"
                    >Message <span class="text-destructive">*</span></Label
                >
                <textarea
                    id="edit-body"
                    v-model="form.body"
                    rows="4"
                    placeholder="Write your announcement..."
                    :disabled="form.processing"
                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                />
                <InputError :message="form.errors.body" />
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <Label for="edit-starts-at">Starts At</Label>
                    <Input
                        id="edit-starts-at"
                        v-model="form.starts_at"
                        type="datetime-local"
                        :disabled="form.processing"
                    />
                    <InputError :message="form.errors.starts_at" />
                </div>

                <div class="space-y-1">
                    <Label for="edit-expires-at">Expires At</Label>
                    <Input
                        id="edit-expires-at"
                        v-model="form.expires_at"
                        type="datetime-local"
                        :disabled="form.processing"
                    />
                    <InputError :message="form.errors.expires_at" />
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-1">
                <Label for="edit-status">Status</Label>
                <select
                    id="edit-status"
                    v-model="form.status"
                    :disabled="form.processing"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <InputError :message="form.errors.status" />
            </div>
        </form>

        <!-- Footer -->
        <template #footer>
            <div class="flex justify-end gap-3">
                <Button
                    type="button"
                    variant="outline"
                    :disabled="form.processing"
                    @click="handleClose"
                >
                    Cancel
                </Button>
                <Button
                    type="button"
                    :disabled="form.processing"
                    @click="handleSubmit"
                >
                    Save Changes
                </Button>
            </div>
        </template>
    </BaseModal>
</template>
