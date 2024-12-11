<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.edit.heading', { name: tool.name })" />

        <PageCard>
            <AlertBox :pending-edit="pendingEdit" />

            <form @submit.prevent="submitAndContinue">
                <ToolForm
                    ref="form"
                    :form.sync="form"
                    :tool="tool"
                    :features="features"
                    :software-types="softwareTypes"
                    :devices="devices"
                    :standards="standards"
                    :operating-systems="operatingSystems"
                    :data-processing-locations="dataProcessingLocations"
                    :certifications="certifications"
                    :working-methods="workingMethods"
                    :target-groups="targetGroups"
                    :complexities="complexities"
                    :countries="countries"
                    :updated-at="tool.updated_at"
                />

                <FormFooter class="flex justify-between mt-6">
                    <Btn
                        target="_blank"
                        :href="route('content-manager.tool.log', { tool: tool.id })"
                        variant="default"
                    >
                        {{ trans('page.content-manager.tool.edit.view_log') }}
                    </Btn>

                    <div class="flex items-center gap-2">
                        <Btn
                            type="button"
                            variant="default-dark"
                            @click="cancel"
                        >
                            {{ trans('action.cancel') }}
                        </Btn>

                        <Btn
                            type="submit"
                            variant="default"
                            :disabled="form.processing"
                            @click="submitAndContinue"
                        >
                            {{ trans('action.store') }}
                        </Btn>

                        <Btn
                            type="button"
                            :variant="!tool.is_published ? `default` : `primary`"
                            :disabled="form.processing"
                            @click="submitAndFinish"
                        >
                            {{ trans('action.store_and_close') }}
                        </Btn>

                        <Btn
                            v-if="!tool.is_published && tool.permissions.publish"
                            class="ml-2"
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
import { router, useForm } from '@inertiajs/vue2';

import Layout from '@/layouts/DefaultLayout';

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';
import PageCard from '@/components/page/PageCard';
import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import ToolForm from '@/pages/content-manager/tool/components/ToolForm';
import AlertBox from '@/components/AlertBox.vue';

import FormErrorsMixin from '@/mixins/form-errors.js';

export default {
    components: {
        AlertBox,
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
        ToolForm,
    },
    mixins: [FormErrorsMixin],
    layout: Layout,
    props: {
        tool: {
            type: Object,
            required: true,
        },
        features: {
            type: [Object, Array],
            required: true,
        },
        softwareTypes: {
            type: [Object, Array],
            required: true,
        },
        devices: {
            type: [Object, Array],
            required: true,
        },
        standards: {
            type: [Object, Array],
            required: true,
        },
        operatingSystems: {
            type: [Object, Array],
            required: true,
        },
        dataProcessingLocations: {
            type: [Object, Array],
            required: true,
        },
        certifications: {
            type: [Object, Array],
            required: true,
        },
        workingMethods: {
            type: [Object, Array],
            required: true,
        },
        targetGroups: {
            type: [Object, Array],
            required: true,
        },
        complexities: {
            type: [Object, Array],
            required: true,
        },
        countries: {
            type: Object,
            required: true,
        },
        pendingEdit: {
            type: Object,
            default: null,
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

                features: this.tool.features,
                software_types: this.tool.software_types,
                devices: this.tool.devices,
                standards: this.tool.standards,
                operating_systems: this.tool.operating_systems,
                data_processing_locations: this.tool.data_processing_locations,
                certifications: this.tool.certifications,
                working_methods: this.tool.working_methods,
                target_groups: this.tool.target_groups,
                complexity: this.tool.complexity,

                name: this.tool.name,
                supplier: this.tool.supplier,
                supplier_url: this.tool.supplier_url,
                description_short_en: this.tool.description_short_en,
                description_short_nl: this.tool.description_short_nl,
                addons_en: this.tool.addons_en,
                addons_nl: this.tool.addons_nl,
                logo_filename: null,
                logo_url: this.tool.logo_url,
                image_1_filename: null,
                image_1_url: this.tool.image_1_url,
                image_2_filename: null,
                image_2_url: this.tool.image_2_url,

                system_requirements_en: this.tool.system_requirements_en,
                system_requirements_nl: this.tool.system_requirements_nl,

                supplier_country: this.tool.supplier_country,
                personal_data_en: this.tool.personal_data_en,
                personal_data_nl: this.tool.personal_data_nl,
                privacy_policy_url: this.tool.privacy_policy_url,
                model_processor_agreement_url: this.tool.model_processor_agreement_url,
                privacy_analysis: this.tool.privacy_analysis,
                supplier_agrees_with_surf_standards: this.tool.supplier_agrees_with_surf_standards,
                dtia_by_external_url: this.tool.dtia_by_external_url,
                dpia_by_external_url: this.tool.dpia_by_external_url,
                jurisdiction: this.tool.jurisdiction,

                instructions_manual_1_url_en: this.tool.instructions_manual_1_url_en,
                instructions_manual_1_url_nl: this.tool.instructions_manual_1_url_nl,
                instructions_manual_2_url_en: this.tool.instructions_manual_2_url_en,
                instructions_manual_2_url_nl: this.tool.instructions_manual_2_url_nl,
                instructions_manual_3_url_en: this.tool.instructions_manual_3_url_en,
                instructions_manual_3_url_nl: this.tool.instructions_manual_3_url_nl,
                support_for_teachers_en: this.tool.support_for_teachers_en,
                support_for_teachers_nl: this.tool.support_for_teachers_nl,
                availability_surf: this.tool.availability_surf,
                accessibility_facilities_en: this.tool.accessibility_facilities_en,
                accessibility_facilities_nl: this.tool.accessibility_facilities_nl,

                description_long_en: this.tool.description_long_en,
                description_long_nl: this.tool.description_long_nl,
                use_for_education_en: this.tool.use_for_education_en,
                use_for_education_nl: this.tool.use_for_education_nl,
                how_does_it_work_en: this.tool.how_does_it_work_en,
                how_does_it_work_nl: this.tool.how_does_it_work_nl,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         *
         * @param {object} props
         * @param {boolean} props.continueEditing
         */
        submit(props = { continueEditing: false }) {
            const postArguments = { tool: this.tool };
            if (props.continueEditing) {
                postArguments.continue = true;
            }

            this.form
                .transform((data) => ({
                    ...data,
                    supplier_agrees_with_surf_standards: data.supplier_agrees_with_surf_standards === true,
                }))
                .post(route('content-manager.tool.update', postArguments), {
                    preserveScroll: props.continueEditing,

                    // eslint-disable-next-line vue/no-undef-properties
                    onError: this.showFirstFormError,
                });
        },
        /**
         * Submit and Finish editing
         *
         */
        submitAndFinish() {
            this.submit({ continueEditing: false });
        },
        /**
         * Submit and Continue editing
         *
         */
        submitAndContinue() {
            this.submit({ continueEditing: true });
        },
        /**
         * Publishes the tool.
         */
        publish() {
            this.form
                .transform((data) => ({
                    ...data,
                    supplier_agrees_with_surf_standards: data.supplier_agrees_with_surf_standards === true,
                }))
                .post(route('content-manager.tool.publish', this.tool), {
                    // eslint-disable-next-line vue/no-undef-properties
                    onError: this.showFirstFormError,
                });
        },
        /** @returns {void} */
        cancel() {
            router.post(route('content-manager.tool.cancel-edit', this.tool));
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
