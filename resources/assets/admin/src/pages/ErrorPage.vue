<template>
    <section class="container | py-16">
        <p class="text-center" v-text="message" />
    </section>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';

export default {
    layout: Layout,
    props: {
        status: {
            type: Number,
            required: true,
        },
    },
    computed: {
        /**
         * Determines the error message based on the status code.
         *
         * @returns {string}
         */
        message() {
            const specificMessages = {
                401: trans('message.error.forbidden'),
                403: trans('message.error.forbidden'),
                404: trans('message.error.not-found'),
                419: trans('message.error.expired'),
                503: trans('message.error.maintenance'),
            };

            if (specificMessages[this.status]) {
                return specificMessages[this.status];
            }

            return trans('message.error.general');
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: this.message,
        };
    },
};
</script>
