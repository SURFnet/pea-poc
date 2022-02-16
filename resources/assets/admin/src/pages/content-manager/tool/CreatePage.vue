<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.create.heading')" />

        <PageCard>
            <form @submit.prevent="submit">
                <ToolForm
                    :features="features"
                    :authentication-methods="authenticationMethods"
                    :stored-data="storedData"
                    :supported-standards="supportedStandards"
                    :form.sync="form"
                />

                <FormFooter align="end" class="mt-6">
                    <Btn
                        inertia
                        :href="route('content-manager.tool.index')"
                        variant="default-dark"
                        v-text="trans('action.cancel')"
                    />

                    <Btn type="submit" variant="primary" :disabled="form.processing">
                        {{ trans('action.store') }}
                    </Btn>
                </FormFooter>
            </form>
        </PageCard>
    </PageContainer>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';
import PageCard from '@/components/page/PageCard';

import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import ToolForm from '@/pages/content-manager/tool/components/ToolForm';

export default {
    components: {
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
        ToolForm,
    },
    layout: Layout,
    props: {
        features: {
            type: Object,
            required: true,
        },
        authenticationMethods: {
            type: Object,
            required: true,
        },
        storedData: {
            type: Object,
            required: true,
        },
        supportedStandards: {
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
            form: this.$inertia.form({
                name: null,
                description_short: null,
                image_filename: null,
                image_url: null,
                features: [],
                supported_standards: [],
                additional_standards: null,
                authentication_methods: [],
                stored_data: [],
                other_stored_data: null,
                european_data_storage: false,
                surf_standards_framework_agreed: false,
                has_processing_agreement: false,
                description_long_1: null,
                description_1_image_filename: null,
                description_1_image_url: null,
                description_long_2: null,
                description_2_image_filename: null,
                description_2_image_url: null,
                info_url: null,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('content-manager.tool.store').url());
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-manager.tool.create.title'),
        };
    },
};
</script>
