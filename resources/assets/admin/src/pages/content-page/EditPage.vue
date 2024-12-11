<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-page.edit.heading', { title: contentPage.title_en })" />

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
import Btn from '@/components/Btn.vue';
import FormFooter from '@/components/FormFooter.vue';

export default {
    components: {
        FormFooter,
        Btn,
        ContentPageForm,
        PageCard,
        PageHeader,
        PageContainer,
    },
    layout: Layout,
    props: {
        contentPage: {
            type: Object,
            required: true,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            form: useForm({
                title_en: this.contentPage.title_en,
                title_nl: this.contentPage.title_nl,
                body_en: this.contentPage.body_en,
                body_nl: this.contentPage.body_nl,
                slug: this.contentPage.slug,
            }),
        };
    },
    methods: {
        /**
         * Submit the form
         */
        submit() {
            this.form.put(route('content-page.update', this.contentPage));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-page.edit.title', { title: this.contentPage.title_en }),
        };
    },
};
</script>
