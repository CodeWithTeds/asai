<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import BaseModal from '@/components/BaseModal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/job-postings';

type JobPosting = {
    id: number;
    title: string;
    description: string;
    location: string | null;
    type: string;
    status: string;
    starts_at: string | null;
    expires_at: string | null;
};

type Props = {
    jobPosting: JobPosting;
};

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('open');

function toLocalInput(date: string | null): string {
    if (!date) {
        return '';
    }

    return new Date(date).toISOString().slice(0, 16);
}

const form = useForm({
    title: props.jobPosting.title,
    description: props.jobPosting.description,
    location: props.jobPosting.location ?? '',
    type: props.jobPosting.type,
    status: props.jobPosting.status,
    starts_at: toLocalInput(props.jobPosting.starts_at),
    expires_at: toLocalInput(props.jobPosting.expires_at),
});

function handleSubmit() {
    form.put(update(props.jobPosting.id).url, {
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
        title="Edit Job Posting"
        description="Update the job posting details."
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
                    placeholder="Job title"
                    :disabled="form.processing"
                />
                <InputError :message="form.errors.title" />
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <Label for="edit-description"
                    >Description <span class="text-destructive">*</span></Label
                >
                <textarea
                    id="edit-description"
                    v-model="form.description"
                    rows="4"
                    placeholder="Describe the role, responsibilities, and requirements..."
                    :disabled="form.processing"
                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                />
                <InputError :message="form.errors.description" />
            </div>

            <!-- Location -->
            <div class="space-y-1">
                <Label for="edit-location">Location</Label>
                <Input
                    id="edit-location"
                    v-model="form.location"
                    placeholder="e.g. Annapolis, MD or Remote"
                    :disabled="form.processing"
                />
                <InputError :message="form.errors.location" />
            </div>

            <!-- Type & Status -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <Label for="edit-type"
                        >Type <span class="text-destructive">*</span></Label
                    >
                    <select
                        id="edit-type"
                        v-model="form.type"
                        :disabled="form.processing"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Contract</option>
                    </select>
                    <InputError :message="form.errors.type" />
                </div>

                <div class="space-y-1">
                    <Label for="edit-status">Status</Label>
                    <select
                        id="edit-status"
                        v-model="form.status"
                        :disabled="form.processing"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                    </select>
                    <InputError :message="form.errors.status" />
                </div>
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
