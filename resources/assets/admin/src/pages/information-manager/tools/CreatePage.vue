<template>
    <div class="container-xl">
        <ToolHeader
            :tool="tool"
            :back-url="backUrl"
            :editing="true"
        />

        <PageContainer>
            <PageCard>
                <form @submit.prevent="submitAndContinue">
                    <ToolForm
                        ref="form"
                        :categories="categories"
                        :data-classifications="dataClassifications"
                        :status-options="statusOptions"
                        :form.sync="form"
                        :alternative-tools="alternativeTools"
                    />

                    <FormFooter
                        align="end"
                        class="mt-6"
                    >
                        <Btn
                            inertia
                            class="mr-4"
                            variant="default-dark"
                            :href="backUrl"
                        >
                            {{ trans('action.cancel') }}
                        </Btn>

                        <Btn
                            type="submit"
                            variant="default-dark"
                            :disabled="form.processing"
                            @click="submitAndContinue"
                        >
                            {{ trans('action.add') }}
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

            <OriginalToolInfo :tool="tool" />
        </PageContainer>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue2';

import Layout from '@/layouts/AdminLayout';

import FormErrorsMixin from '@/mixins/form-errors.js';

import PageContainer from '@/components/page/PageContainer';
import PageCard from '@/components/page/PageCard';
import ToolHeader from '@/pages/our/tool/components/ToolHeader';
import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import ToolForm from '@/pages/information-manager/tools/components/ToolForm';
import OriginalToolInfo from '@/pages/information-manager/tools/components/OriginalToolInfo';

export default {
    components: {
        OriginalToolInfo,
        PageContainer,
        PageCard,
        ToolHeader,
        Btn,
        FormFooter,
        ToolForm,
    },
    mixins: [FormErrorsMixin],
    layout: Layout,
    props: {
        alternativeTools: {
            type: [Array, Object],
            required: true,
        },
        backUrl: {
            type: String,
            required: true,
        },
        categories: {
            type: [Object, Array],
            required: true,
        },
        statusOptions: {
            type: Object,
            required: true,
        },
        dataClassifications: {
            type: Object,
            required: true,
        },
        tool: {
            type: Object,
            required: true,
        },
        customFields: {
            type: Array,
            required: true,
        },
        defaultStatus: {
            type: String,
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
                alternative_tools_ids: [],
                status: this.defaultStatus,
                categories: [],
                custom_fields: this.customFields,
                conditions_en: null,
                conditions_nl: null,

                links_with_other_tools_en: null,
                links_with_other_tools_nl: null,
                sla_url: null,

                privacy_contact: null,
                privacy_evaluation_url: null,
                security_evaluation_url: null,
                data_classification: null,

                how_to_login_en: null,
                how_to_login_nl: null,
                availability_en: null,
                availability_nl: null,
                licensing_en: null,
                licensing_nl: null,
                request_access_en: null,
                request_access_nl: null,
                instructions_en: null,
                instructions_nl: null,
                instructions_manual_1_url: null,
                instructions_manual_2_url: null,
                instructions_manual_3_url: null,

                faq_en: null,
                faq_nl: null,
                examples_of_usage_en: null,
                examples_of_usage_nl: null,
                additional_info_heading_en: null,
                additional_info_heading_nl: null,
                additional_info_text_en: null,
                additional_info_text_nl: null,

                why_unfit_en: null,
                why_unfit_nl: null,
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
                    why_unfit_nl: data.status === 'disallowed' ? data.why_unfit_nl : null,
                    why_unfit_en: data.status === 'disallowed' ? data.why_unfit_en : null,
                }))
                .post(route('information-manager.tool.store', postArguments), {
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
            title: trans('page.information-manager.tool.create.title', { tool: this.tool.name }),
        };
    },
};
</script>
