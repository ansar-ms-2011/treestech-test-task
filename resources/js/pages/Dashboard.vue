<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard State',
        href: '/dashboard',
    },
];
const props = defineProps({
    total: {
        type: Number,
        default: 0,
    },
    completed: {
        type: Number,
        default: 0,
    },
    in_progress: {
        type: Number,
        default: 0,
    },
    pending: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div class="bg-blue-200 flex justify-center items-center relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="font-semibild text-2xl">Total : {{ props.total }}</h2>
                    <PlaceholderPattern />
                </div>
                <div class="bg-gray-300 flex justify-center items-center relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="font-semibild text-2xl">Pending : {{ props.pending }}</h2>
                    <PlaceholderPattern />
                </div>
                <div class="bg-green-200 flex justify-center items-center relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="font-semibild text-2xl">In Progress : {{ props.in_progress }}</h2>
                    <PlaceholderPattern />
                </div>
                <div class="bg-indigo-100 flex justify-center items-center relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="font-semibild text-2xl">Completed: {{ props.completed }}</h2>
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="flex justify-center items-center  relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
