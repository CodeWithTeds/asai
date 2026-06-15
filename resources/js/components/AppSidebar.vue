<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Briefcase, LayoutGrid, Megaphone, FileText } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as announcementsIndex } from '@/routes/announcements';
import { index as jobApplicationsIndex } from '@/routes/job-applications';
import { index as jobPostingsIndex } from '@/routes/job-postings';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Announcements',
        href: announcementsIndex(),
        icon: Megaphone,
    },
    {
        title: 'Job Postings',
        href: jobPostingsIndex(),
        icon: Briefcase,
    },
    {
        title: 'Applications',
        href: jobApplicationsIndex(),
        icon: FileText,
    },
];

const footerNavItems: NavItem[] = [
    //
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader
            class="px-4 py-6 transition-all duration-200 group-data-[collapsible=icon]:px-0 group-data-[collapsible=icon]:py-2"
        >
            <Link
                :href="dashboard()"
                class="flex w-full items-center justify-center"
            >
                <AppLogo
                    class="h-28 w-full max-w-[200px] transition-all duration-200 group-data-[collapsible=icon]:size-8"
                />
            </Link>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
