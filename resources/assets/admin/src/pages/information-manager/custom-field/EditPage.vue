<template>
    <PageContainer>
        <PageHeader
            :title="trans('page.information-manager.custom-field.edit.heading', { title: customField.title })"
        />

        <PageCard>
            <form @submit.prevent="submit">
                <CustomFieldForm
                    :form.sync="form"
                    :tab-types="tabTypes"
                />

                <FormFooter
                    align="end"
                    class="mt-6"
                >
                    <Btn
                        inertia
                        variant="default-dark"
                        class="mr-4"
                        :href="route('information-manager.custom-field.index')"
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

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';
import PageCard from '@/components/page/PageCard';
import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import CustomFieldForm from '@/pages/information-manager/custom-field/components/CustomFieldForm.vue';

export default {
    components: {
        CustomFieldForm,
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
    },
    layout: Layout,
    props: {
        customField: {
            type: Object,
            required: true,
        },
        tabTypes: {
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
                _method: 'put',
                title_en: this.customField.title_en,
                title_nl: this.customField.title_nl,
                sortkey: this.customField.sortkey,
                tab_type: this.customField.tab_type,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.custom-field.update', this.customField));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.custom-field.edit.title', { title: this.customField.title }),
        };
    },
};
</script>
