<template>
    <div>
        <nav class="space-y-6">
            <Btn :href="route('auth.surf.login')" variant="primary" class="w-full | justify-center">
                {{ trans('action.login-with-surfconext') }}
            </Btn>

            <Btn
                v-if="canLoginAsSuperAdmin"
                variant="primary"
                as="button"
                class="w-full | justify-center"
                @click="loginAsSuperAdmin"
            >
                {{ trans('action.login-as-super-admin') }}
            </Btn>
        </nav>
    </div>
</template>

<script>
import Layout from '@/layouts/AuthLayout';
import Btn from '@/components/Btn';

export default {
    components: {
        Btn,
    },
    layout: Layout,
    props: {
        canLoginAsSuperAdmin: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        /**
         * Handles the form submission.
         */
        loginAsSuperAdmin() {
            this.$inertia.post(route('account.login-as-super-admin'));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return { title: trans('page.account.login.title') };
    },
};
</script>
