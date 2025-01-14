<template>
    <div>
        <div class="flex flex-col md:flex-row justify-between mb-8 gap-4 text-center md:text-left">
            <div>
                <h1 class="text-2xl font-bold uppercase text-orange-500">User Administration</h1>
                <p>Manage users in the system.</p>
            </div>
            <div>
                <button class="border border-orange-500 text-orange-500 rounded-lg px-4 py-2 hover:bg-orange-500 hover:text-white transition duration-300" @click="showCreateUserModal">Create User</button>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            
            <thead>
                <tr>
                    <th class="text-left p-2 uppercase"></th>
                    <th class="text-left p-2 uppercase">Name</th>
                    <th class="text-left p-2 uppercase">Email</th>
                    <th class="text-left p-2 uppercase">Role</th>
                    <th class="text-right p-2 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="user.id" :class="{'bg-gray-100': index % 2 === 0}">
                    <td v-if="user.deleted_at" class="p-2"><span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span></td>
                    <td v-if="!user.deleted_at" class="p-2"><span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span></td>
                    <td class="p-2">{{ user.first_name }} {{ user.last_name }}</td>
                    <td class="p-2">{{ user.email }}</td>
                    <td class="p-2">{{ user.rid && roles[Number(user.rid)] }}</td>
                    <td class="p-2 flex justify-end gap-2">
                        <button class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 text-xs hover:bg-gray-300 transition duration-300" @click="showEditUserModal(user)">Edit</button>
                        
                        <button class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 text-xs hover:bg-gray-300 transition duration-300" @click="deleteUser(user?.id || '')">{{ user.deleted_at? 'Undelete' : 'Delete' }}</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <WebSite />

        <!-- Create/Edit User Modal -->
        <ModalComponent
            :isVisible="showModal"
            @close="closeModal"
            
        >
            <template #header>
                <h2>{{ isEditMode ? 'Edit User' : 'Create User' }}</h2>
            </template>
            <template #body>
                <form @submit.prevent="isEditMode ? updateUser(form?.id || '', form) : createUser(form)">
                    <div class="mb-4">
                        <label for="first-name" class="block text-sm font-medium text-gray-700">First:</label>
                        <input placeholder="First Name" id="first-name" type="text" v-model="form.first_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="last-name" class="block text-sm font-medium text-gray-700">Name:</label>
                        <input placeholder="Last Name" id="last-name" type="text" v-model="form.last_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input placeholder="Email Address" id="email" type="email" v-model="form.email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                        <input id="password" type="password" v-model="form.password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role:</label>
                        <select id="role" v-model="form.rid" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            <option v-for="(role, id) in roles" :key="id" :value="id" :selected="form?.rid === id.toString()">{{ role }}</option>
                        </select>
                    </div>
                    <div v-if="error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline">{{ error }}</span>
                    </div>
                    <div class="flex flex-col md:flex-row justify-end gap-2">
                        <button v-if="isEditMode" type="button" @click="deleteUser(form.id || '')" class="w-full md:w-auto border border-gray-500 text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-500 hover:text-white transition duration-300">Delete</button>
                        <button v-if="form.deleted_at" type="button" @click="deleteUser(form.id || '')" class="w-full md:w-auto border border-gray-500 text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-500 hover:text-white transition duration-300">Undelete</button>
                        <button type="submit" class="w-full md:w-auto bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition duration-300">{{ isEditMode ? 'Update' : 'Create' }}</button>
                        <button type="button" @click="closeModal" class="w-full md:w-auto bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">Cancel</button>
                    </div>
                </form>
            </template>
        </ModalComponent>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import { defineComponent, onMounted, ref } from 'vue';
import { IUser } from '../Dashboard.vue';
import ModalComponent from '@/Components/ModalComponent.vue';
import WebSite from './Admin/WebSite.vue';

export default defineComponent({
    components: {
        ModalComponent,
        WebSite
    },
    name: 'AdminDashboard',
    props: {
        account: {
            type: Object as () => {
                id: string;
                first_name: string;
                last_name: string;
                email: string;
                role: object;
            },
            required: true
        }
    },
    setup() {
        const defUser: IUser = {
            created_at: '',
            email: '',
            email_verified_at: '',
            first_name: '',
            last_name: '',
            rid:  '',
            status: 'inactive',
            updated_at: '',
        };
        const users = ref<IUser[]>([]);
        const roles = ref<{ [key: number]: string }>({});
        const error = ref<string | null>(null);
        const showModal = ref(false);
        const isEditMode = ref(false);
        const form = ref<IUser>(defUser);

        const fetchUsers = () => {
            axios.get('/api/users').then(r => {
                const rRev: { [key: number]: string } = {};
                r.data.roles?.forEach((role: { type: string; id: number; description: string }) => {
                    rRev[role.id] = role.description;
                });
                roles.value = rRev;
                users.value = r.data.data;
            }).catch(err => {
                console.error('Error fetching users:', err);
            });
        };

        const showCreateUserModal = () => {
            error.value = null;
            isEditMode.value = false;
            form.value = defUser;
            showModal.value = true;
        };

        const showEditUserModal = (user: IUser) => {
            error.value = null;
            isEditMode.value = true;
            form.value = { ...user };
            showModal.value = true;
        };

        const createUser = (form: any) => {
            error.value = null;
            axios.post('/api/users', form).then((r) => {
                if (r?.data?.error) {
                    error.value = r.data.error;
                }
                fetchUsers();
            }).catch(err => {
                console.error('Error creating user:', err);
            });
        };

        const updateUser = (id: string, form: any) => {
            axios.patch(`/api/users/${id}`, form).then(() => {
                fetchUsers();
            }).catch(err => {
                console.error('Error updating user:', err);
            });
        };

        const deleteUser = (id: string) => {
            axios.delete(`/api/users/${id}`).then(() => {
                fetchUsers();
            }).catch(err => {
                console.error('Error deleting user:', err);
            });
        };

        const closeModal = () => {
            showModal.value = false;
        };

        onMounted(() => {
            fetchUsers();
        });

        return {
            users,
            roles,
            error,
            showModal,
            isEditMode,
            form,
            fetchUsers,
            showCreateUserModal,
            showEditUserModal,
            createUser,
            updateUser,
            deleteUser,
            closeModal
        };
    }
});
</script>