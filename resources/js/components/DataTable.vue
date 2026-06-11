<script setup lang="ts">
import { Ellipsis } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

type Column = {
    key: string;
    label: string;
    align?: 'left' | 'center' | 'right';
    width?: string;
};

type ActionItem = {
    label: string;
    variant?: 'default' | 'destructive';
    handler: (row: Record<string, any>) => void;
    show?: (row: Record<string, any>) => boolean;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Props = {
    columns: Column[];
    rows: Record<string, any>[];
    actions?: ActionItem[];
    currentPage?: number;
    lastPage?: number;
    perPage?: number;
    total?: number;
    links?: PaginationLink[];
    emptyMessage?: string;
};

const props = withDefaults(defineProps<Props>(), {
    actions: () => [],
    currentPage: 1,
    lastPage: 1,
    perPage: 15,
    total: 0,
    links: () => [],
    emptyMessage: 'No data available.',
});

function alignClass(align?: string) {
    if (align === 'center') return 'text-center';
    if (align === 'right') return 'text-right';
    return 'text-left';
}

function getVisibleActions(row: Record<string, any>) {
    return props.actions.filter((a) => !a.show || a.show(row));
}
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-border bg-card shadow-xs">
        <table class="w-full table-fixed text-sm">
            <!-- Table header -->
            <thead class="bg-muted/50">
                <tr class="border-b border-border">
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        :class="[
                            alignClass(col.align),
                            'px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-muted-foreground/80',
                        ]"
                        :style="col.width ? { width: col.width } : undefined"
                    >
                        {{ col.label }}
                    </th>
                    <th v-if="actions.length > 0" class="w-12 px-4 py-3" />
                </tr>
            </thead>

            <!-- Table body -->
            <tbody class="divide-y divide-border/60">
                <!-- Empty state -->
                <tr v-if="rows.length === 0">
                    <td
                        :colspan="columns.length + (actions.length > 0 ? 1 : 0)"
                        class="px-5 py-20 text-center"
                    >
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-sm text-muted-foreground">{{ emptyMessage }}</span>
                        </div>
                    </td>
                </tr>

                <!-- Data rows -->
                <tr
                    v-for="(row, idx) in rows"
                    :key="row.id ?? idx"
                    class="group bg-card transition-colors duration-100 hover:bg-muted/30"
                >
                    <td
                        v-for="col in columns"
                        :key="col.key"
                        :class="[alignClass(col.align), 'px-5 py-3.5']"
                    >
                        <slot
                            :name="`cell-${col.key}`"
                            :row="row"
                            :value="row[col.key]"
                        >
                            {{ row[col.key] ?? '—' }}
                        </slot>
                    </td>

                    <!-- Actions column -->
                    <td
                        v-if="actions.length > 0"
                        class="px-4 py-3.5 text-right"
                    >
                        <DropdownMenu v-if="getVisibleActions(row).length > 0">
                            <DropdownMenuTrigger as-child>
                                <button
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground/60 transition-colors hover:bg-muted hover:text-foreground focus:outline-none focus-visible:ring-2 focus-visible:ring-ring/50"
                                >
                                    <Ellipsis class="h-4 w-4" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-36">
                                <DropdownMenuItem
                                    v-for="action in getVisibleActions(row)"
                                    :key="action.label"
                                    :variant="action.variant"
                                    @click="action.handler(row)"
                                >
                                    {{ action.label }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="lastPage > 1" class="border-t border-border bg-muted/30 px-5">
            <Pagination
                :current-page="currentPage"
                :last-page="lastPage"
                :per-page="perPage"
                :total="total"
                :links="links"
            />
        </div>
    </div>
</template>
