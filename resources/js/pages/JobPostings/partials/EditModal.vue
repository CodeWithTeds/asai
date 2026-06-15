<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { XIcon, ImagePlusIcon } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import BaseModal from '@/components/BaseModal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/job-postings';

type JobPosting = {
    id: number;
    title: string;
    description: string;
    cover_image: string | null;
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
    cover_image: null as File | null,
    remove_cover_image: false,
});

// Image preview state
const fileInput = ref<HTMLInputElement | null>(null);
const previewUrl = ref<string | null>(null);
const previewLoading = ref(false);

// Preview priority: local > server > nothing
const displayUrl = computed<string | null>(() => {
    if (previewUrl.value) {
        return previewUrl.value;
    }

    if (form.remove_cover_image) {
        return null;
    }

    return props.jobPosting.cover_image
        ? `/storage/${props.jobPosting.cover_image}`
        : null;
});

const hasImage = computed(
    () => previewLoading.value || displayUrl.value !== null,
);

function handleFileChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;

    if (!file) {
        return;
    }

    form.cover_image = file;
    form.remove_cover_image = false;
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
    if (previewUrl.value) {
        // Drop new file pick, restore server image
        previewUrl.value = null;
        form.cover_image = null;

        if (fileInput.value) {
            fileInput.value.value = '';
        }
    } else {
        // Mark server image for removal
        form.remove_cover_image = true;
    }

    previewLoading.value = false;
}

function handleSubmit() {
    form.post(update(props.jobPosting.id).url, {
        forceFormData: true,

        headers: { 'X-HTTP-Method-Override': 'PUT' } as any,
        onSuccess: () => {
            isOpen.value = false;
        },
    });
}

function handleClose() {
    isOpen.value = false;
    form.clearErrors();
    previewUrl.value = null;
    previewLoading.value = false;
    form.remove_cover_image = false;
    form.cover_image = null;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Edit Job Posting"
        size="4xl"
        @update:open="handleClose"
    >
        <form
            class="grid min-h-full grid-cols-[1fr_360px] items-stretch gap-6"
            @submit.prevent="handleSubmit"
        >
            <!-- Left — Fields -->
            <div class="flex flex-col gap-3">
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
                        >Description
                        <span class="text-destructive">*</span></Label
                    >
                    <textarea
                        id="edit-description"
                        v-model="form.description"
                        rows="5"
                        placeholder="Role, responsibilities, and requirements..."
                        :disabled="form.processing"
                        class="flex min-h-[140px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <Label for="edit-type"
                            >Type <span class="text-destructive">*</span></Label
                        >
                        <select
                            id="edit-type"
                            v-model="form.type"
                            :disabled="form.processing"
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-3">
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
            </div>

            <!-- Right — Cover image -->
            <div class="flex flex-col overflow-hidden">
                <Label class="mb-1.5 block">Cover Image</Label>

                <!-- Loading shimmer -->
                <div
                    v-if="previewLoading"
                    class="shimmer relative mt-2 min-h-[320px] w-full flex-1 overflow-hidden rounded-lg"
                />

                <!-- Preview (new pick or existing server image) -->
                <div
                    v-else-if="hasImage && displayUrl"
                    class="relative mt-2 min-h-[320px] w-full flex-1 overflow-hidden rounded-lg border border-input bg-muted/40"
                >
                    <img
                        :src="displayUrl"
                        alt="Cover preview"
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

                <!-- Upload zone -->
                <label
                    v-else
                    class="relative mt-2 flex min-h-[320px] w-full flex-1 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-black/25 bg-black/[0.03] p-3 text-center text-muted-foreground transition-colors hover:border-black/40 hover:bg-black/[0.06]"
                    :class="{
                        'pointer-events-none cursor-not-allowed opacity-50':
                            form.processing,
                    }"
                >
                    <ImagePlusIcon class="mb-2 size-7 opacity-40" />
                    <span class="text-sm font-medium">Upload cover image</span>
                    <span class="mt-0.5 text-xs opacity-60"
                        >JPG, PNG or WebP · max 2 MB</span
                    >
                    <input
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="sr-only"
                        :disabled="form.processing"
                        @change="handleFileChange"
                    />
                </label>

                <InputError :message="form.errors.cover_image" />
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
                    Save Changes
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
