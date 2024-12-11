<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.tag.create.heading')" />

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
                        variant="default-dark"
                        class="mr-4"
                        :href="route('information-manager.tag.index')"
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

import Layout from '@/layouts/AdminLayout';

import PageContainer from '@/components/page/PageContainer.vue';
import PageHeader from '@/components/page/PageHeader.vue';
import PageCard from '@/components/page/PageCard.vue';
import FormFooter from '@/components/FormFooter.vue';
import Btn from '@/components/Btn.vue';
import TagForm from '@/pages/information-manager/tag/components/TagForm.vue';

export default {
    components: {
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
        TagForm,
    },
    layout: Layout,
    props: {
        tagTypes: {
            type: Object,
            required: true,
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
                name: { nl: null, en: null },
                description: { nl: null, en: null },
                type: 'categories',
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.tag.store'));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.tag.create.heading'),
        };
    },
};
</script>
