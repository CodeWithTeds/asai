<script setup lang="ts">
import { ref, watch } from 'vue';
import { Search, ChevronDown, X } from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

export type FilterOption = {
    label: string;
    value: string;
};

export type FilterGroup = {
    key: string;
    label: string;
    options: FilterOption[];
};

type Props = {
    search?: string;
    filters?: Record<string, string>;
    filterGroups?: FilterGroup[];
    searchPlaceholder?: string;
    debounce?: number;
};

const props = withDefaults(defineProps<Props>(), {
    search: '',
    filters: () => ({}),
    filterGroups: () => [],
    searchPlaceholder: 'Search…',
    debounce: 350,
});

const emit = defineEmits<{
    'update:search': [value: string];
    'update:filters': [value: Record<string, string>];
}>();

const localSearch = ref(props.search);
const localFilters = ref<Record<string, string>>({ ...props.filters });

// Sync with parent props
watch(() => props.search, (newSearch) => {
    localSearch.value = newSearch;
});

watch(() => props.filters, (newFilters) => {
    localFilters.value = { ...newFilters };
}, { deep: true });

// Debounced search emit
let searchTimer: ReturnType<typeof setTimeout> | null = null;

watch(localSearch, (val) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        emit('update:search', val);
    }, props.debounce);
});

function selectFilter(groupKey: string, value: string) {
    // If same value clicked again, deselect (clear)
    if (localFilters.value[groupKey] === value) {
        localFilters.value = { ...localFilters.value, [groupKey]: '' };
    } else {
        localFilters.value = { ...localFilters.value, [groupKey]: value };
    }

    emit('update:filters', localFilters.value);
}

function clearGroupFilter(groupKey: string) {
    localFilters.value = { ...localFilters.value, [groupKey]: '' };
    emit('update:filters', localFilters.value);
}

function hasActiveFilter(groupKey: string): boolean {
    return !!localFilters.value[groupKey];
}

function hasAnyActiveFilter(): boolean {
    return Object.values(localFilters.value).some((v) => !!v);
}

function clearAllFilters() {
    const cleared: Record<string, string> = {};
    for (const key of Object.keys(localFilters.value)) {
        cleared[key] = '';
    }
    localFilters.value = cleared;
    emit('update:filters', localFilters.value);
}

function activeLabel(groupKey: string): string | undefined {
    const val = localFilters.value[groupKey];
    if (!val) return undefined;

    const group = props.filterGroups.find((g) => g.key === groupKey);
    return group?.options.find((o) => o.value === val)?.label;
}

function clearSearch() {
    localSearch.value = '';
    emit('update:search', '');
}
</script>

<template>
    <div class="flex items-center gap-2">
        <!-- Filter dropdowns — left side -->
        <DropdownMenu
            v-for="group in filterGroups"
            :key="group.key"
        >
            <DropdownMenuTrigger as-child>
                <button
                    class="inline-flex h-9 items-center gap-1.5 rounded-md border border-input bg-transparent px-3 text-sm text-muted-foreground shadow-xs transition-colors hover:bg-muted hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/50"
                    :class="hasActiveFilter(group.key) ? 'border-primary text-foreground' : ''"
                >
                    <span>{{ activeLabel(group.key) ?? group.label }}</span>

                    <ChevronDown class="h-3.5 w-3.5 opacity-50" />
                </button>
            </DropdownMenuTrigger>

            <DropdownMenuContent align="start" class="w-44">
                <DropdownMenuRadioGroup :model-value="localFilters[group.key] ?? ''">
                    <DropdownMenuRadioItem
                        v-for="option in group.options"
                        :key="option.value"
                        :value="option.value"
                        @select.prevent="selectFilter(group.key, option.value)"
                    >
                        {{ option.label }}
                    </DropdownMenuRadioItem>
                </DropdownMenuRadioGroup>
            </DropdownMenuContent>
        </DropdownMenu>

        <!-- Single clear button — shows only when any filter is active -->
        <button
            v-if="hasAnyActiveFilter()"
            class="inline-flex h-9 items-center gap-1.5 rounded-md border border-input px-3 text-sm text-muted-foreground shadow-xs transition-colors hover:bg-destructive/10 hover:text-destructive focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/50"
            @click="clearAllFilters"
        >
            Clear
            <X class="h-3.5 w-3.5" />
        </button>

        <!-- Spacer: pushes search + actions to the right -->
        <div class="ml-auto flex items-center gap-2">
            <!-- Search input — right side, fixed width -->
            <div class="relative w-56">
                <Search
                    class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-muted-foreground"
                />
                <input
                    v-model="localSearch"
                    type="text"
                    :placeholder="searchPlaceholder"
                    class="h-9 w-full rounded-md border border-input bg-transparent pl-9 pr-8 text-sm text-foreground shadow-xs placeholder:text-muted-foreground transition-[color,box-shadow] focus-visible:border-ring focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/50"
                />
                <button
                    v-if="localSearch"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded text-muted-foreground/70 transition-colors hover:text-foreground"
                    @click="clearSearch"
                >
                    <X class="h-3.5 w-3.5" />
                </button>
            </div>

            <!-- Action slot (immediately beside search) -->
            <slot name="actions" />
        </div>
    </div>
</template>
