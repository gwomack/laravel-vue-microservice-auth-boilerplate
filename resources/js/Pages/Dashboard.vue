<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                    Welcome to your Dashboard
                </h2>
                <button
                    @click="logout"
                    class="btn-secondary"
                >
                    Logout
                </button>
            </div>

            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <!-- User Avatar/Initial -->
                        <div class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center">
                            <span class="text-white text-lg font-semibold">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </span>
                        </div>

                        <!-- User Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $page.props.auth.user.name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $page.props.auth.user.email }}
                            </p>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-4">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                            Account Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ new Date($page.props.auth.user.created_at).toLocaleDateString() }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email Status</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $page.props.auth.user.email_verified_at ? 'Verified' : 'Not Verified' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-4">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                            Quick Actions
                        </h4>
                        <div class="flex space-x-4">
                            <Link
                                :href="route('profile.edit')"
                                class="btn-primary text-sm"
                            >
                                Edit Profile
                            </Link>
                            <button
                                @click="logout"
                                class="btn-secondary text-sm"
                            >
                                Sign Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
