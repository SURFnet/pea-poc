<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tag.create.heading')" />

        <PageCard>
            <form @submit.prevent="submit">
                <TagForm
                    :form.sync="form"
                    :tag-types="tagTypes"
                />

                <FormFooter
                    align="end"
                    class="mt-6"
                >
                    <Btn
                        inertia
                        :href="route('content-manager.tool.index')"
                        variant="default-dark"
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

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';
import PageCard from '@/components/page/PageCard';
import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import TagForm from '@/pages/content-manager/tag/components/TagForm.vue';

export default {
    components: {
        TagForm,
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
    },
    layout: Layout,
    props: {
        tagTypes: {
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
                name: { nl: null, en: null },
                description: { nl: null, en: null },
                type: null,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('content-manager.tag.store'));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-manager.tag.create.title'),
        };
    },
};
</script>
