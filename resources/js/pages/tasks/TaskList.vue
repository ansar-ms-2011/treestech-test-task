<template>
    <Head title="Tasks List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mt-4 flex justify-between">
                <h1>Tasks</h1>
                <Button @click="showCreateForm = true" v-if="isAdmin">Create New Task</Button>
            </div>
            <p v-if="flashedMessages && flashedMessages.success" class="text-green-600">
                {{ flashedMessages.success }}
            </p>
            <p v-if="flashedMessages && flashedMessages.error" class="text-red-600">
                {{ flashedMessages.success }}
            </p>
            <table class="min-w-full divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Due Date</th>
                        <th class="px-4 py-3">Priority</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Created By</th>
                        <th class="px-4 py-3">Assigned To</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-sm">
                    <tr v-for="task in tasks" :key="task.id">
                        <td class="px-4 py-3">{{ task.title }}</td>
                        <td class="px-4 py-3">{{ task.description }}</td>
                        <td class="px-4 py-3">{{ formatDate(task.due_date) }}</td>
                        <td class="px-4 py-3">
                            <span class="mr-2 inline-block rounded capitalize bg-green-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                {{ task.priority }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="mr-2 inline-block rounded capitalize bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                {{ task.status.replace('_', ' ') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ task.user ? task.user.name : '' }}</td>
                        <td class="px-4 py-3">{{ task.assigned_to ? task.assigned_to.name : 'Unassigned' }}</td>
                        <td class="px-4 py-3">
                            <Button class="ml-1 rounded bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700" @click="editTask(task)"
                                >Edit</Button
                            >
                            <Button
                                v-if="isAdmin"
                                class="ml-1 rounded bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700"
                                @click="deleteTask(task.id)"
                                >Delete</Button
                            >
                        </td>
                    </tr>
                </tbody>
            </table>

            <TaskForm v-if="showCreateForm" @close="showCreateForm = false" @created="handleTaskCreated" :users="users" />
            <TaskForm v-if="editingTask" :task="editingTask" @close="editingTask = null" @updated="handleTaskUpdated" :users="users" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import echo from '@/echo.js';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onBeforeMount, ref } from 'vue';
import { toast } from 'vue3-toastify';
import TaskForm from './TaskForm.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks List',
        href: '/tasks/list',
    },
];

const page = usePage();
const users = page.props.users;
const roles = page.props.auth.roles;
const isAdmin = roles.includes('admin');
const isManager = roles.includes('manager');
const isUser = roles.includes('user');

const flashedMessages = page.props.flash;
const tasks = ref([]);
const showCreateForm = ref(false);
const editingTask = ref(null);

onBeforeMount(() => {
    fetchTasks();
});

echo.channel('treestech-test-task').listen('.status.updated', (e) => {
    console.log('Task updated:', e.task);
    fetchTasks();
    toast.success('🎉 Task Status updated', {
        className: 'bg-green-500 text-white font-medium rounded shadow',
    });
});

const fetchTasks = async () => {
    const response = await axios.get('/api/tasks');
    console.log(response.data);
    tasks.value = response.data;
};
const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};
const editTask = (task) => {
    editingTask.value = { ...task };
    console.log(task);
};

const deleteTask = async (id) => {
    if (confirm('Are you sure you want to delete this task?')) {
        await axios.delete(`/api/tasks/${id}`);
        await fetchTasks();
    }
};
const handleTaskCreated = () => {
    console.log('Task created successfully!');
    showCreateForm.value = false;
    fetchTasks();
};
const handleTaskUpdated = () => {
    console.log('Task updated successfully!');
    editingTask.value = null;
    fetchTasks();
};
</script>
