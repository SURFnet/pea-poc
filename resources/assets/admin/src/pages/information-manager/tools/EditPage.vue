<template>
    <div class="container-xl">
        <ToolHeader
            :tool="tool"
            :back-url="backUrl"
            :editing="true"
        />

        <PageContainer>
            <PageCard>
                <AlertBox :pending-edit="pendingEdit" />

                <form @submit.prevent="submitAndContinue">
                    <ToolForm
                        ref="form"
                        :categories="categories"
                        :data-classifications="dataClassifications"
                        :status-options="statusOptions"
                        :form.sync="form"
                        :alternative-tools="alternativeTools"
                        :prohibited-alternative-tools="instituteTool.prohibited_alternative_tools_tool"
                    />

                    <FormFooter class="flex justify-between mt-6">
                        <Btn
                            target="_blank"
                            :href="route('information-manager.tool.log', { tool: tool.id })"
                            variant="default"
                        >
                            {{ trans('page.information-manager.tool.edit.view_log') }}
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
                                v-if="instituteTool.is_published && instituteTool.permissions.publish"
                                type="button"
                                class="mr-2"
                                variant="warning"
                                :disabled="form.processing"
                                @click="unpublish"
                            >
                                {{ trans('action.unpublish') }}
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
                                :variant="!instituteTool.is_published ? `default` : `primary`"
                                :disabled="form.processing"
                                @click="submitAndFinish"
                            >
                                {{ trans('action.store_and_close') }}
                            </Btn>

                            <Btn
                                v-if="!instituteTool.is_published && instituteTool.permissions.publish"
                                type="button"
                                class="ml-2"
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

            <OriginalToolInfo :tool="tool" />
        </PageContainer>
    </div>
</template>

<script>
import { router, useForm } from '@inertiajs/vue2';

import Layout from '@/layouts/AdminLayout';

import PageContainer from '@/components/page/PageContainer';
import PageCard from '@/components/page/PageCard';
import ToolHeader from '@/pages/our/tool/components/ToolHeader';
import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import ToolForm from '@/pages/information-manager/tools/components/ToolForm';
import AlertBox from '@/components/AlertBox.vue';
import OriginalToolInfo from '@/pages/information-manager/tools/components/OriginalToolInfo.vue';

import FormErrorsMixin from '@/mixins/form-errors.js';

export default {
    components: {
        OriginalToolInfo,
        AlertBox,
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
        instituteTool: {
            type: Object,
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
            type: [Object, Array],
            required: true,
        },
        tool: {
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

                alternative_tools_ids: this.instituteTool.alternative_tools_ids ?? [],

                status: this.instituteTool.status,
                categories: this.instituteTool.categories,
                custom_fields: this.instituteTool.custom_fields,
                conditions_en: this.instituteTool.conditions_en,
                conditions_nl: this.instituteTool.conditions_nl,

                links_with_other_tools_en: this.instituteTool.links_with_other_tools_en,
                links_with_other_tools_nl: this.instituteTool.links_with_other_tools_nl,
                sla_url: this.instituteTool.sla_url,

                privacy_contact: this.instituteTool.privacy_contact,
                privacy_evaluation_url: this.instituteTool.privacy_evaluation_url,
                security_evaluation_url: this.instituteTool.security_evaluation_url,
                data_classification: this.instituteTool.data_classification,

                how_to_login_en: this.instituteTool.how_to_login_en,
                how_to_login_nl: this.instituteTool.how_to_login_nl,
                availability_en: this.instituteTool.availability_en,
                availability_nl: this.instituteTool.availability_nl,
                licensing_en: this.instituteTool.licensing_en,
                licensing_nl: this.instituteTool.licensing_nl,
                request_access_en: this.instituteTool.request_access_en,
                request_access_nl: this.instituteTool.request_access_nl,
                instructions_en: this.instituteTool.instructions_en,
                instructions_nl: this.instituteTool.instructions_nl,
                instructions_manual_1_url: this.instituteTool.instructions_manual_1_url,
                instructions_manual_2_url: this.instituteTool.instructions_manual_2_url,
                instructions_manual_3_url: this.instituteTool.instructions_manual_3_url,

                faq_en: this.instituteTool.faq_en,
                faq_nl: this.instituteTool.faq_nl,
                examples_of_usage_en: this.instituteTool.examples_of_usage_en,
                examples_of_usage_nl: this.instituteTool.examples_of_usage_nl,
                additional_info_heading_en: this.instituteTool.additional_info_heading_en,
                additional_info_heading_nl: this.instituteTool.additional_info_heading_nl,
                additional_info_text_en: this.instituteTool.additional_info_text_en,
                additional_info_text_nl: this.instituteTool.additional_info_text_nl,

                why_unfit_en: this.instituteTool.why_unfit_en,
                why_unfit_nl: this.instituteTool.why_unfit_nl,
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
                .post(route('information-manager.tool.update', postArguments), {
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
         * Publishes the tool for the institute.
         */
        unpublish() {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.unpublish-tool'))) {
                return;
            }

            this.form.post(route('information-manager.tool.unpublish', this.tool), {
                onError: this.showFirstFormError,
            });
        },
        /**
         * Publishes the tool for the institute.
         */
        publish() {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.publish-tool'))) {
                return;
            }

            this.form.post(route('information-manager.tool.publish', this.tool), {
                onError: this.showFirstFormError,
            });
        },
        /** @returns {void} */
        cancel() {
            router.post(route('information-manager.tool.cancel-edit', this.tool));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.tool.edit.title', { tool: this.tool.name }),
        };
    },
};
</script>
