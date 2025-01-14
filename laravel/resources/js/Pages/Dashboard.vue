<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminDashboard from './Dashboard/Admin.vue';
import StaffDashboard from './Dashboard/Staff.vue';
import RandomBookSuggestions from '@/Components/RandomBookSuggestions.vue';
import MemberRentals from './Dashboard/MemberRentals.vue';

interface IRole
{
    created_at: string;
    deleted_at: string;
    description: string;
    id: string;
    type: string;
    updated_at: string;
}

export interface IUser
{
    created_at: string;
    email: string;
    email_verified_at: string;
    first_name: string;
    last_name: string;
    id?: string;
    rid?: string;
    role?: IRole;
    status: 'active' | 'inactive';
    updated_at: string;
    deleted_at?: string;
    password?: string;
}

// Get the user object from the page props
const { props } = usePage<any>();
const user = computed(() => props.auth.user);
const { role }: IUser = user.value;
// Define computed properties for role-based views
const isMember = computed(() => role?.type === 'member');
const isLibrarian = computed(() => role?.type === 'librarian');
const isAdmin = computed(() => role?.type === 'admin');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl leading-tight text-gray-800">
                <span v-if="isMember">Welcome <b class="text-orange-500">{{user.first_name}} {{user.last_name}}</b>.</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="isMember">
                            <RandomBookSuggestions />
                            <MemberRentals />
                        </div>
                        <div v-else-if="isLibrarian">
                            <!-- Librarian view content -->
                            <StaffDashboard />
                        </div>
                        <div v-else-if="isAdmin">
                            <AdminDashboard :account="user" />
                        </div>
                        <div v-else>
                            <!-- Default view content -->
                            <p>You're logged in!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>