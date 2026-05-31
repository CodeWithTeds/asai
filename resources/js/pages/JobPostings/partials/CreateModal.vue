<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import BaseModal from '@/components/BaseModal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { store } from '@/routes/job-postings';

const isOpen = defineModel<boolean>('open');

function nowLocal(): string {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

const form = useForm({
    title: '',
    description: '',
    location: '',
    type: 'full_time',
    status: 'open',
    starts_at: nowLocal(),
    expires_at: '',
});

function handleSubmit() {
    form.post(store().url, {
        onSuccess: () => {
            isOpen.value = false;
            form.reset();
        },
    });
}

function handleClose() {
    isOpen.value = false;
    form.reset();
    form.clearErrors();
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Create Job Posting"
        description="Fill in the details for the new job posting."
        size="lg"
        @update:open="handleClose"
    >
        <form class="space-y-4" @submit.prevent="handleSubmit">
            <!-- Title -->
            <div class="space-y-1">
                <Label for="create-title"
                    >Title <span class="text-destructive">*</span></Label
                >
                <Input
                    id="create-title"
                    v-model="form.title"
                    placeholder="Job title"
                    :disabled="form.processing"
                />
                <InputError :message="form.errors.title" />
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <Label for="create-description"
                    >Description <span class="text-destructive">*</span></Label
                >
                <textarea
                    id="create-description"
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
                <Label for="create-location">Location</Label>
                <Input
                    id="create-location"
                    v-model="form.location"
                    placeholder="e.g. Annapolis, MD or Remote"
                    :disabled="form.processing"
                />
                <InputError :message="form.errors.location" />
            </div>

            <!-- Type & Status -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <Label for="create-type"
                        >Type <span class="text-destructive">*</span></Label
                    >
                    <select
                        id="create-type"
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
                    <Label for="create-status">Status</Label>
                    <select
                        id="create-status"
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
                    <Label for="create-starts-at">Starts At</Label>
                    <Input
                        id="create-starts-at"
                        v-model="form.starts_at"
                        type="datetime-local"
                        :disabled="form.processing"
                    />
                    <InputError :message="form.errors.starts_at" />
                </div>

                <div class="space-y-1">
                    <Label for="create-expires-at">Expires At</Label>
                    <Input
                        id="create-expires-at"
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
                    Create
                </Button>
            </div>
        </template>
    </BaseModal>
</template>
