<template>
    <!-- Overlay -->
    <div class="bg-light-grey bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center">
        <div class="modal mx-auto w-1/2 rounded-lg bg-white p-4 shadow-xl">
            <div class="mb-4 flex flex-row items-center justify-between">
                <h1 class="text-lg font-semibold">{{ task ? 'Edit Task' : 'Create Task' }}</h1>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form @submit.prevent="submitForm">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="title">Name</Label>
                        <Input
                            :disabled="isManager || isUser"
                            id="title"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="title"
                            v-model="form.title"
                            placeholder="Title of Task"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Description</Label>
                        <Textarea
                            :disabled="isManager"
                            id="description"
                            required
                            :tabindex="2"
                            autocomplete="description"
                            v-model="form.description"
                            placeholder="Description"
                            :rows="4"
                        ></Textarea>
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="priority">Priority</Label>
                            <Select
                                :disabled="isManager|| isUser"
                                :options="[
                                    { value: null, label: '--Select--' },
                                    { value: 'low', label: 'Low' },
                                    { value: 'medium', label: 'Medium' },
                                    { value: 'high', label: 'High' },
                                ]"
                                id="priority"
                                name="priority"
                                :tabindex="3"
                                v-model="form.priority"
                            ></Select>
                            <InputError :message="form.errors.priority" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="status">Status</Label>
                            <Select
                                :disabled="isUser"
                                :options="[
                                    { value: null, label: '--Select--' },
                                    { value: 'pending', label: 'Pending' },
                                    { value: 'in_progress', label: 'In Progress' },
                                    { value: 'completed', label: 'Completed' },
                                ]"
                                id="priority"
                                name="status"
                                :tabindex="4"
                                v-model="form.status"
                            ></Select>
                            <InputError :message="form.errors.status" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="status">Assigned To</Label>
                            <Select
                            :disabled="isUser"
                                :options="[{ value: null, label: '--Select--' }, ...users]"
                                id="assigned_to_id"
                                name="assigned_to_id"
                                :tabindex="4"
                                v-model="form.assigned_to_id"
                            ></Select>
                            <InputError :message="form.errors.status" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password">Due Date</Label>
                            <Input
                                :disabled="isManager || isUser"
                                id="dues-date"
                                type="date"
                                required
                                :tabindex="5"
                                autocomplete="dues-date"
                                v-model="form.due_date"
                                placeholder="Due Date"
                            ></Input>
                            <InputError :message="form.errors.due_date" />
                        </div>
                    </div>

                    <Button type="submit" class="mt-2 w-full" tabindex="6" :disabled="form.processing"> Save Task </Button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Overlay -->
</template>

<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const roles = page.props.auth.roles;
const isAdmin = roles.includes('admin');
const isManager = roles.includes('manager');
const isUser = roles.includes('user');

const emit = defineEmits(['created', 'updated', 'close']);
const props = defineProps({
    task: {
        type: Object,
        default: null,
    },
    users: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    _method: props.task ? 'PUT' : 'POST',
    id: props.task ? props.task.id : null,
    title: props.task ? props.task.title : '',
    description: props.task ? props.task.description : '',
    due_date: props.task ? props.task.due_date.split('T')[0] : '',
    priority: props.task ? props.task.priority : '',
    status: props.task ? props.task.status : '',
    user_id: props.task ? props.task.user_id : page.props.auth.user.id,
    assigned_to_id: props.task ? props.task.assigned_to_id : '',
});

const minDate = () => {
    return new Date().toISOString().split('T')[0];
};

const submitForm = async () => {
    try {
        form.clearErrors();
        if (form.id > 0) {
            await form.post('/api/tasks/' + form.id, {
                onSuccess: () => {
                    emit('updated', form);
                    emit('close');
                },
            });
        } else {
            await form.post('/api/tasks', {
                onSuccess: () => {
                    emit('created', form);
                    emit('close');
                },
            });
        }
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>

<style>
.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border: 1px solid #ccc;
    z-index: 1000;
}
</style>
