<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { store } from '@/routes/announcements';
import BaseModal from '@/components/BaseModal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const isOpen = defineModel<boolean>('open');

const form = useForm({
    title: '',
    body: '',
    is_active: true,
    starts_at: '',
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
        title="Create Announcement"
        description="Fill in the details for the new announcement."
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
                    placeholder="Announcement title"
                    :disabled="form.processing"
                />
                <InputError :message="form.errors.title" />
            </div>

            <!-- Body -->
            <div class="space-y-1">
                <Label for="create-body"
                    >Message <span class="text-destructive">*</span></Label
                >
                <textarea
                    id="create-body"
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

            <!-- Active toggle -->
            <div class="flex items-center gap-2">
                <input
                    id="create-is-active"
                    v-model="form.is_active"
                    type="checkbox"
                    :disabled="form.processing"
                    class="h-4 w-4 rounded border-gray-300"
                />
                <Label for="create-is-active" class="cursor-pointer"
                    >Active</Label
                >
            </div>

            <InputError :message="form.errors.is_active" />
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
