<template>
    <div
        class="min-h-screen | bg-app | flex flex-col justify-center | font-sans text-gray-800 | antialiased | py-12 sm:px-6 lg:px-8"
    >
        <NotificationContainer />

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <BrandLogo class="mb-6" />

            <!-- eslint-disable-next-line vue/no-v-html -->
            <h1 class="text-xs text-center" v-html="trans('page.login.disclaimer')" />
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-lg | mt-8">
            <div class="bg-white shadow-lg sm:rounded-lg | py-8 px-4 sm:px-14">
                <slot />
            </div>
        </div>

        <AuthFooter :app-name="trans('page.app-name')" class="text-xs text-center" />
    </div>
</template>

<script>
import NotificationContainer from '@/layouts/components/NotificationContainer';
import BrandLogo from '@/components/BrandLogo';
import AuthFooter from '@/layouts/components/AuthFooter';

export default {
    components: {
        AuthFooter,
        NotificationContainer,
        BrandLogo,
    },
};
</script>
