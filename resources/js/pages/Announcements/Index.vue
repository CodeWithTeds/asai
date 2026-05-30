<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { index } from '@/routes/announcements';
import { Button } from '@/components/ui/button';
import CreateModal from './partials/CreateModal.vue';
import DeleteModal from './partials/DeleteModal.vue';
import EditModal from './partials/EditModal.vue';
import { Announcement } from '@/types';

type PaginatedAnnouncements = {
    data: Announcement[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

defineProps<{ announcements: PaginatedAnnouncements }>();

const authUserId = computed(() => usePage().props.auth.user?.id);

function isOwner(announcement: Announcement) {
    return authUserId.value === announcement.creator.id;
}

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Announcements', href: index().url }],
    },
});

// Modal state
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedAnnouncement = ref<Announcement | null>(null);

function openEdit(announcement: Announcement) {
    selectedAnnouncement.value = announcement;
    showEditModal.value = true;
}

function openDelete(announcement: Announcement) {
    selectedAnnouncement.value = announcement;
    showDeleteModal.value = true;
}

function formatDate(date: string | null) {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
</script>

<template>
    <Head title="Announcements" />

    <div class="flex flex-col gap-6 p-4">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Announcements</h1>
                <p class="text-sm text-muted-foreground">
                    Manage public announcements shown on the welcome page.
                </p>
            </div>
            <Button @click="showCreateModal = true">New Announcement</Button>
        </div>

        <!-- Table -->
        <div class="rounded-lg border">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th class="px-4 py-3 text-left font-medium">Title</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">
                            Starts At
                        </th>
                        <th class="px-4 py-3 text-left font-medium">
                            Expires At
                        </th>
                        <th class="px-4 py-3 text-left font-medium">
                            Created By
                        </th>
                        <th class="px-4 py-3 text-right font-medium">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Empty state -->
                    <tr v-if="announcements.data.length === 0">
                        <td
                            colspan="6"
                            class="px-4 py-10 text-center text-muted-foreground"
                        >
                            No announcements yet.
                        </td>
                    </tr>

                    <tr
                        v-for="announcement in announcements.data"
                        :key="announcement.id"
                        class="border-b transition-colors last:border-b-0 hover:bg-muted/30"
                    >
                        <td class="px-4 py-3 font-medium">
                            {{ announcement.title }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                :class="
                                    announcement.is_active
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                        : 'bg-muted text-muted-foreground'
                                "
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                            >
                                {{
                                    announcement.is_active
                                        ? 'Active'
                                        : 'Inactive'
                                }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ formatDate(announcement.starts_at) }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ formatDate(announcement.expires_at) }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ announcement.creator.name }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <template v-if="isOwner(announcement)">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        @click="openEdit(announcement)"
                                    >
                                        Edit
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="destructive"
                                        @click="openDelete(announcement)"
                                    >
                                        Delete
                                    </Button>
                                </template>
                                <span
                                    v-else
                                    class="text-xs text-muted-foreground"
                                    >—</span
                                >
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="announcements.last_page > 1"
            class="flex items-center justify-end gap-1"
        >
            <template v-for="link in announcements.links" :key="link.label">
                <a
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    :class="[
                        'rounded border px-3 py-1 text-sm transition-colors',
                        link.active
                            ? 'border-primary bg-primary text-primary-foreground'
                            : 'border-border hover:bg-muted',
                    ]"
                />
                <span
                    v-else
                    v-html="link.label"
                    class="rounded border border-border px-3 py-1 text-sm text-muted-foreground opacity-50"
                />
            </template>
        </div>
    </div>

    <!-- Modals -->
    <CreateModal v-model:open="showCreateModal" />

    <EditModal
        v-if="selectedAnnouncement"
        v-model:open="showEditModal"
        :announcement="selectedAnnouncement"
    />

    <DeleteModal
        v-if="selectedAnnouncement"
        v-model:open="showDeleteModal"
        :announcement="selectedAnnouncement"
    />
</template>
