<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.create.heading')" />

        <PageCard>
            <form @submit.prevent="submitAndContinue">
                <ToolForm
                    ref="form"
                    :form.sync="form"
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
                        variant="default"
                        :disabled="form.processing"
                        @click="submitAndContinue"
                    >
                        {{ trans('action.store') }}
                    </Btn>

                    <Btn
                        type="button"
                        variant="primary"
                        :disabled="form.processing"
                        @click="submitAndFinish"
                    >
                        {{ trans('action.store_and_close') }}
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
import ToolForm from '@/pages/content-manager/tool/components/ToolForm';

import FormErrorsMixin from '@/mixins/form-errors.js';

export default {
    components: {
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
        prefills: {
            type: Object,
            default: null,
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
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            form: useForm({
                name: '',
                supplier: '',
                supplier_url: '',
                description_short_en: '',
                description_short_nl: '',
                features: [],
                addons_en: '',
                addons_nl: '',
                logo_filename: '',
                logo_url: '',
                image_1_filename: '',
                image_1_url: '',
                image_2_filename: '',
                image_2_url: '',

                software_types: [],
                devices: [],
                system_requirements_en: '',
                system_requirements_nl: '',
                standards: [],
                operating_systems: [],

                supplier_country: '',
                personal_data_en: '',
                personal_data_nl: '',
                data_processing_locations: [],
                privacy_policy_url: '',
                model_processor_agreement_url: '',
                privacy_analysis: this.prefills.privacy_analysis ? this.prefills.privacy_analysis : '',
                supplier_agrees_with_surf_standards: false,
                certifications: [],
                dtia_by_external_url: '',
                dpia_by_external_url: '',
                jurisdiction: '',

                instructions_manual_1_url_en: '',
                instructions_manual_1_url_nl: '',
                instructions_manual_2_url_en: '',
                instructions_manual_2_url_nl: '',
                instructions_manual_3_url_en: '',
                instructions_manual_3_url_nl: '',
                support_for_teachers_en: '',
                support_for_teachers_nl: '',
                availability_surf: '',
                accessibility_facilities_en: '',
                accessibility_facilities_nl: '',

                description_long_en: '',
                description_long_nl: '',
                use_for_education_en: this.prefills.use_for_education_en ? this.prefills.use_for_education_en : '',
                use_for_education_nl: this.prefills.use_for_education_nl ? this.prefills.use_for_education_nl : '',
                working_methods: [],
                target_groups: [],
                how_does_it_work_en: '',
                how_does_it_work_nl: '',
                complexity: [],
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
            const postArguments = {};
            if (props.continueEditing) {
                postArguments.continue = true;
            }

            this.form
                .transform((data) => ({
                    ...data,
                    supplier_agrees_with_surf_standards: data.supplier_agrees_with_surf_standards === true,
                }))
                .post(route('content-manager.tool.store', postArguments), {
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
