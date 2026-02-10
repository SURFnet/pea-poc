<template>
    <div>
        <nav class="space-y-6">
            <Btn
                :href="route('auth.surf.login')"
                variant="primary"
                class="w-full | justify-center"
            >
                {{ trans('action.login-with-surfconext') }}
            </Btn>

            <template v-if="allowTestUserLogin">
                <p>{{ trans('auth.test-users') }}:</p>

                <Btn
                    v-for="role in supportedTestRoles"
                    :key="role"
                    variant="primary"
                    as="button"
                    class="w-full justify-center"
                    @click="loginAsTestUser(role)"
                >
                    {{ trans('action.login-as-test-user', { role: trans(`auth.roles.${role}`) }) }}
                </Btn>
            </template>
        </nav>
    </div>
</template>

<script>
import { router } from '@inertiajs/vue2';

import Layout from '@/layouts/AuthLayout';
import Btn from '@/components/Btn';

export default {
    components: {
        Btn,
    },
    layout: Layout,
    props: {
        allowTestUserLogin: {
            type: Boolean,
            default: false,
        },
        supportedTestRoles: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        /**
         * @param {string} role
         */
        loginAsTestUser(role) {
            router.post(route('account.login-as-test-user'), {
                role,
            });
        },
    },
    /**
     * The reactive meta info object.
     *
     * @returns {object}
     */
    metaInfo() {
        return { title: trans('page.account.login.title') };
    },
};
</script>
