<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.edit.heading', { name: tool.name })" />

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

                    <div class="space-x-2">
                        <Btn
                            type="submit"
                            :variant="!tool.is_published ? `default` : `primary`"
                            :disabled="form.processing"
                        >
                            {{ trans('action.store') }}
                        </Btn>
                        <Btn
                            v-if="!tool.is_published && tool.permissions.publish"
                            type="button"
                            variant="primary"
                            :disabled="form.processing"
                            @click="publish"
                        >
                            {{ trans('action.publish') }}
                        </Btn>
                    </div>
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
        tool: {
            type: Object,
            required: true,
        },
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
                _method: 'put',
                name: this.tool.name,
                description_short: this.tool.description_short,
                image_filename: null,
                image_url: this.tool.image_url,
                features: this.tool.features.map((feature) => feature.id),
                supported_standards: this.tool.supported_standards,
                additional_standards: this.tool.additional_standards,
                authentication_methods: this.tool.authentication_methods,
                stored_data: this.tool.stored_data,
                other_stored_data: this.tool.other_stored_data,
                european_data_storage: this.tool.european_data_storage,
                surf_standards_framework_agreed: this.tool.surf_standards_framework_agreed,
                has_processing_agreement: this.tool.has_processing_agreement,
                description_long_1: this.tool.description_long_1,
                description_1_image_filename: null,
                description_1_image_url: this.tool.description_1_image_url,
                description_long_2: this.tool.description_long_2,
                description_2_image_filename: null,
                description_2_image_url: this.tool.description_2_image_url,
                info_url: this.tool.info_url,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('content-manager.tool.update', this.tool).url());
        },

        /**
         * Publishes the tool.
         */
        publish() {
            this.form.post(route('content-manager.tool.publish', this.tool).url());
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-manager.tool.edit.title', { name: this.tool.name }),
        };
    },
};
</script>
