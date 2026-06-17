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
import { store } from '@/routes/events/manage';

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
    type: 'event',
    status: 'active',
    starts_at: nowLocal(),
    expires_at: '',
    images: [] as File[],
});

// Image preview state
const fileInput = ref<HTMLInputElement | null>(null);
const previews = ref<Array<{ url: string; file: File }>>([]);

function handleFileChange(event: Event) {
    const files = (event.target as HTMLInputElement).files;

    if (!files) {
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        form.images.push(file);

        const reader = new FileReader();
        reader.onload = (e) => {
            previews.value.push({
                url: e.target?.result as string,
                file: file,
            });
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(index: number) {
    form.images.splice(index, 1);
    previews.value.splice(index, 1);

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
            previews.value = [];
        },
    });
}

function handleClose() {
    isOpen.value = false;
    form.reset();
    form.clearErrors();
    previews.value = [];
}
</script>

<template>
    <BaseModal
        v-model:open="isOpen"
        title="Create Event"
        description="Fill in the details for the new event."
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
                    <Label for="create-title"
                        >Title <span class="text-destructive">*</span></Label
                    >
                    <Input
                        id="create-title"
                        v-model="form.title"
                        placeholder="Event title"
                        :disabled="form.processing"
                        class="h-10"
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
                        rows="6"
                        placeholder="Write your event..."
                        :disabled="form.processing"
                        class="flex min-h-[190px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="event">Event</option>
                            <option value="activity">Activity</option>
                            <option value="news">News</option>
                            <option value="promos">Promos</option>
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
                            class="h-10"
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
                            class="h-10"
                        />
                        <InputError :message="form.errors.expires_at" />
                    </div>
                </div>
            </div>

            <!-- Right — Images -->
            <div class="flex flex-col overflow-hidden">
                <Label class="mb-1.5 block">Event Gallery</Label>

                <!-- Large Placeholder when no images are added -->
                <label
                    v-if="previews.length === 0"
                    class="relative mt-2 flex aspect-video w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-muted-foreground/25 bg-muted/20 p-6 text-center transition-all duration-300 hover:border-violet-500/50 hover:bg-violet-500/[0.02] dark:hover:bg-violet-500/[0.01]"
                    :class="{
                        'pointer-events-none opacity-50': form.processing,
                    }"
                >
                    <div
                        class="flex size-12 items-center justify-center rounded-full bg-violet-100 text-violet-600 dark:bg-violet-950/40 dark:text-violet-400"
                    >
                        <ImagePlusIcon class="size-6" />
                    </div>
                    <span class="mt-3 text-sm font-semibold text-foreground"
                        >No images added</span
                    >
                    <span class="mt-1 text-xs text-muted-foreground"
                        >Click to upload event photos (JPG, PNG or WebP)</span
                    >
                    <span
                        class="mt-4 rounded-md bg-foreground/5 px-2 py-0.5 text-[10px] font-medium text-muted-foreground"
                        >max 2 MB per file</span
                    >
                    <input
                        ref="fileInput"
                        type="file"
                        multiple
                        accept="image/jpeg,image/png,image/webp"
                        class="sr-only"
                        :disabled="form.processing"
                        @change="handleFileChange"
                    />
                </label>

                <!-- Previews Grid + Small trigger inside when images exist -->
                <div v-else class="mt-2 max-h-[360px] overflow-y-auto pr-1">
                    <div class="grid grid-cols-2 gap-2.5">
                        <div
                            v-for="(prev, index) in previews"
                            :key="index"
                            class="group relative aspect-video overflow-hidden rounded-lg border border-input bg-muted/40"
                        >
                            <img
                                :src="prev.url"
                                alt="Image preview"
                                class="h-full w-full object-cover"
                            />
                            <button
                                type="button"
                                :disabled="form.processing"
                                class="absolute top-1.5 right-1.5 flex size-6 items-center justify-center rounded-full bg-black/60 text-white opacity-0 transition-all duration-200 group-hover:opacity-100 hover:bg-black/80 disabled:opacity-50"
                                aria-label="Remove image"
                                @click="removeImage(index)"
                            >
                                <XIcon class="size-3.5" />
                            </button>
                        </div>

                        <!-- Small Add photo trigger card -->
                        <label
                            class="relative flex aspect-video cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-muted-foreground/20 bg-muted/10 text-center transition-all duration-200 hover:border-violet-500/40 hover:bg-violet-500/[0.01]"
                            :class="{
                                'pointer-events-none opacity-50':
                                    form.processing,
                            }"
                        >
                            <ImagePlusIcon
                                class="size-5 text-muted-foreground opacity-60"
                            />
                            <span
                                class="mt-1 text-[11px] font-medium text-muted-foreground"
                                >Add photo</span
                            >
                            <input
                                type="file"
                                multiple
                                accept="image/jpeg,image/png,image/webp"
                                class="sr-only"
                                :disabled="form.processing"
                                @change="handleFileChange"
                            />
                        </label>
                    </div>
                </div>

                <InputError :message="form.errors.images" />
                <InputError
                    v-for="(err, key) in form.errors"
                    :key="key"
                    v-show="key.startsWith('images.')"
                    :message="err"
                />
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
