<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.custom-field.create.heading')" />

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
import CustomFieldForm from '@/pages/information-manager/custom-field/components/CustomFieldForm';

export default {
    components: {
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
        CustomFieldForm,
    },
    layout: Layout,
    props: {
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
                title_en: null,
                title_nl: null,
                sortkey: null,
                tab_type: null,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.custom-field.store'));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.custom-field.create.title'),
        };
    },
};
</script>
