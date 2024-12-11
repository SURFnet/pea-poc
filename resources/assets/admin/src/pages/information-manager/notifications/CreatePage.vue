<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.notifications.heading')" />

        <form @submit.prevent="submit">
            <NotificationForm
                :tools="tools"
                :form.sync="form"
            />

            <FormFooter align="end">
                <Btn
                    type="submit"
                    variant="primary"
                    :disabled="!canSend || form.processing"
                >
                    {{ trans('action.send') }}
                </Btn>
            </FormFooter>
        </form>
    </PageContainer>
</template>
<script>
import { useForm } from '@inertiajs/vue2';

import Layout from '@/layouts/AdminLayout';

import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';
import NotificationForm from '@/pages/information-manager/notifications/components/NotificationForm';

export default {
    components: {
        Btn,
        FormFooter,
        NotificationForm,
        PageContainer,
        PageHeader,
    },
    layout: Layout,
    props: {
        tools: {
            type: Object,
            required: true,
        },
        tool: {
            type: Object,
            default: null,
        },
    },
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            form: useForm({
                tool: this.tool ? this.tool.id : null,
                subject: '',
                message: '',
            }),
        };
    },
    computed: {
        /**
         * Determine if the send button should be enabled
         *
         * @returns {boolean}
         */
        canSend() {
            return this.form.tool !== null && this.form.subject !== '' && this.form.message !== '';
        },
    },
    methods: {
        /**
         * Send the notification
         *
         * @returns {void}
         */
        submit() {
            this.form.post(route('information-manager.notifications.send'), {
                onSuccess: () => {
                    this.form.reset();
                },
            });
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.notifications.title'),
        };
    },
};
</script>
