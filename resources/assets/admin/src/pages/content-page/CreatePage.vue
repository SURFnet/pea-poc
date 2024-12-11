<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-page.create.heading')" />

        <PageCard>
            <form @submit.prevent="submit">
                <ContentPageForm :form.sync="form" />

                <FormFooter
                    align="end"
                    class="mt-6"
                >
                    <Btn
                        inertia
                        variant="default-dark"
                        class="mr-4"
                        :href="route('content-page.index')"
                    >
                        {{ trans('action.cancel') }}
                    </Btn>

                    <Btn
                        type="submit"
                        variant="primary"
                        :disabled="form.processing"
                    >
                        {{ trans('action.store') }}
                    </Btn>
                </FormFooter>
            </form>
        </PageCard>
    </PageContainer>
</template>

<script>
import { useForm } from '@inertiajs/vue2';

import Layout from '@/layouts/DefaultLayout';

import PageContainer from '@/components/page/PageContainer.vue';
import PageHeader from '@/components/page/PageHeader.vue';
import PageCard from '@/components/page/PageCard.vue';
import ContentPageForm from '@/pages/content-page/components/ContentPageForm.vue';
import FormFooter from '@/components/FormFooter.vue';
import Btn from '@/components/Btn.vue';

export default {
    components: {
        Btn,
        FormFooter,
        ContentPageForm,
        PageCard,
        PageHeader,
        PageContainer,
    },
    layout: Layout,
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            form: useForm({
                title_en: null,
                title_nl: null,
                body_en: null,
                body_nl: null,
                slug: null,
            }),
        };
    },
    methods: {
        /**
         * Submit the form
         */
        submit() {
            this.form.post(route('content-page.store'));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-page.create.heading'),
        };
    },
};
</script>
