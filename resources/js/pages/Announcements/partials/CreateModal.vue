<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { XIcon, ImagePlusIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import BaseModal from '@/components/BaseModal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/announcements';

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
    body: '',
    type: 'general',
    status: 'active',
    starts_at: nowLocal(),
    expires_at: '',
    image: null as File | null,
});

// Image preview state
const fileInput = ref<HTMLInputElement | null>(null);
const previewUrl = ref<string | null>(null);
const previewLoading = ref(false);

function handleFileChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;

    if (!file) {
        return;
    }

    form.image = file;
    previewLoading.value = true;

    const reader = new FileReader();
    reader.onload = (e) => {
        // Shimmer for 400ms before preview
        setTimeout(() => {
            previewUrl.value = e.target?.result as string;
            previewLoading.value = false;
        }, 400);
    };
    reader.readAsDataURL(file);
}

function clearImage() {
    form.image = null;
    previewUrl.value = null;
    previewLoading.value = false;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function handleSubmit() {
    form.post(store().url, {
        forceFormData: true,
        onSuccess: () => {
            isOpen.value = false;
            form.reset();
            clearImage();
        },
    });
}

function handleClose() {
    isOpen.value = false;
    form.reset();
    form.clearErrors();
    clearImage();
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Create Announcement"
        description="Fill in the details for the new announcement."
        size="2xl"
        @update:open="handleClose"
    >
        <form
            class="grid grid-cols-[1fr_200px] items-stretch gap-6"
            @submit.prevent="handleSubmit"
        >
            <!-- Left — Fields -->
            <div class="flex flex-col gap-3">
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
                        rows="3"
                        placeholder="Write your announcement..."
                        :disabled="form.processing"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <InputError :message="form.errors.body" />
                </div>

                <!-- Type & Status -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <Label for="create-type"
                            >Type <span class="text-destructive">*</span></Label
                        >
                        <select
                            id="create-type"
                            v-model="form.type"
                            :disabled="form.processing"
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="general">General</option>
                            <option value="event">Event</option>
                            <option value="activity">Activity</option>
                            <option value="news">News</option>
                            <option value="alert">Alert</option>
                        </select>
                        <InputError :message="form.errors.type" />
                    </div>

                    <div class="space-y-1">
                        <Label for="create-status">Status</Label>
                        <select
                            id="create-status"
                            v-model="form.status"
                            :disabled="form.processing"
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-3">
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
            </div>

            <!-- Right — Image -->
            <div class="flex flex-col overflow-hidden">
                <Label class="mb-1.5 block">Image</Label>

                <!-- Shimmer while loading -->
                <div
                    v-if="previewLoading"
                    class="shimmer relative mt-2 min-h-[180px] w-full flex-1 overflow-hidden rounded-lg"
                />

                <!-- Image preview -->
                <div
                    v-else-if="previewUrl"
                    class="relative mt-2 min-h-[180px] w-full flex-1 overflow-hidden rounded-lg border border-input bg-muted/40"
                >
                    <img
                        :src="previewUrl"
                        alt="Image preview"
                        class="absolute inset-0 h-full w-full object-contain"
                    />
                    <button
                        type="button"
                        :disabled="form.processing"
                        class="absolute top-1.5 right-1.5 flex size-6 items-center justify-center rounded-full bg-black/60 text-white transition-colors hover:bg-black/80 disabled:opacity-50"
                        aria-label="Remove image"
                        @click="clearImage"
                    >
                        <XIcon class="size-3.5" />
                    </button>
                </div>

                <!-- File upload zone -->
                <label
                    v-else
                    class="relative mt-2 flex min-h-[180px] w-full flex-1 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-black/25 bg-black/[0.03] p-3 text-center text-muted-foreground transition-colors hover:border-black/40 hover:bg-black/[0.06]"
                    :class="{
                        'pointer-events-none cursor-not-allowed opacity-50':
                            form.processing,
                    }"
                >
                    <ImagePlusIcon class="mb-2 size-7 opacity-40" />
                    <span class="text-sm font-medium">Upload image</span>
                    <span class="mt-0.5 text-xs opacity-60"
                        >JPG, PNG or WebP · max 2 MB</span
                    >
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="sr-only"
                        :disabled="form.processing"
                        @change="handleFileChange"
                    />
                </label>

                <InputError :message="form.errors.image" />
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
                    <Spinner v-if="form.processing" class="mr-2" />
                    Create
                </Button>
            </div>
        </template>
    </BaseModal>
</template>

<style scoped>
/* Shimmer sweep */
.shimmer {
    background: linear-gradient(
        90deg,
        hsl(var(--muted)) 25%,
        hsl(var(--muted) / 0.6) 50%,
        hsl(var(--muted)) 75%
    );
    background-size: 200% 100%;
    animation: shimmer 1.2s infinite;
}

@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }

    100% {
        background-position: -200% 0;
    }
}
</style>
