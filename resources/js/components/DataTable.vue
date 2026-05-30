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
    if (align === 'center') {
        return 'text-center';
    }

    if (align === 'right') {
        return 'text-right';
    }

    return 'text-left';
}

function getVisibleActions(row: Record<string, any>) {
    return props.actions.filter((a) => !a.show || a.show(row));
}
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-border">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-muted/50">
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        :class="[
                            alignClass(col.align),
                            'px-5 py-3 text-left text-xs font-semibold text-muted-foreground',
                        ]"
                        :style="col.width ? { width: col.width } : undefined"
                    >
                        {{ col.label }}
                    </th>
                    <th v-if="actions.length > 0" class="w-10 px-3 py-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="rows.length === 0">
                    <td
                        :colspan="columns.length + (actions.length > 0 ? 1 : 0)"
                        class="px-5 py-14 text-center text-muted-foreground"
                    >
                        {{ emptyMessage }}
                    </td>
                </tr>

                <tr
                    v-for="(row, idx) in rows"
                    :key="row.id ?? idx"
                    :class="[idx % 2 === 1 ? 'bg-muted/30' : 'bg-card']"
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

                    <td
                        v-if="actions.length > 0"
                        class="px-3 py-3.5 text-center"
                    >
                        <DropdownMenu v-if="getVisibleActions(row).length > 0">
                            <DropdownMenuTrigger as-child>
                                <button
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                >
                                    <Ellipsis class="h-4 w-4 rotate-90" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-36">
                                <DropdownMenuItem
                                    v-for="action in getVisibleActions(row)"
                                    :key="action.label"
                                    :class="
                                        action.variant === 'destructive'
                                            ? 'text-destructive focus:text-destructive'
                                            : ''
                                    "
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

        <div v-if="lastPage > 1" class="border-t border-border bg-card px-5">
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
