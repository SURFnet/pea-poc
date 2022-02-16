<template>
    <div class="container-xl">
        <ToolHeader :tool="tool" :back-url="backUrl" />

        <PageContainer>
            <PageCard>
                <form @submit.prevent="submit">
                    <ToolForm
                        ref="form"
                        :categories="categories"
                        :institute="institute"
                        :status-options="statusOptions"
                        :form.sync="form"
                        :change-to-prohibited-url="route('information-manager.tool.prohibited.edit', { tool: tool.id })"
                    />

                    <FormFooter align="end" class="mt-6">
                        <Btn
                            inertia
                            class="mr-4"
                            variant="default-dark"
                            :href="backUrl"
                            v-text="trans('action.cancel')"
                        />

                        <div class="space-x-2">
                            <Btn
                                v-if="instituteTool.is_published && instituteTool.permissions.publish"
                                type="button"
                                variant="warning"
                                :disabled="form.processing"
                                @click="unpublish"
                            >
                                {{ trans('action.unpublish') }}
                            </Btn>
                            <Btn
                                type="submit"
                                :variant="!instituteTool.is_published ? `default` : `primary`"
                                :disabled="form.processing"
                            >
                                {{ trans('action.store') }}
                            </Btn>
                            <Btn
                                v-if="!instituteTool.is_published && instituteTool.permissions.publish"
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
    </div>
</template>

<script>
import Layout from '@/layouts/AdminLayout';

import PageContainer from '@/components/page/PageContainer';
import PageCard from '@/components/page/PageCard';
import ToolHeader from '@/pages/our/tool/components/ToolHeader';

import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import ToolForm from '@/pages/information-manager/tools/components/ToolForm';
import FormErrorsMixin from '@/mixins/form-errors.js';

export default {
    components: {
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
        backUrl: {
            type: String,
            required: true,
        },
        institute: {
            type: Object,
            required: true,
        },
        instituteTool: {
            type: Object,
            required: true,
        },
        statusOptions: {
            type: Object,
            required: true,
        },
        tool: {
            type: Object,
            required: true,
        },
        categories: {
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
                categories: this.instituteTool.categories,
                description_1: this.instituteTool.description_1,
                description_1_image_filename: null,
                description_1_image_url: this.instituteTool.description_1_image_url,
                description_2: this.instituteTool.description_2,
                description_2_image_filename: null,
                description_2_image_url: this.instituteTool.description_2_image_url,
                extra_information_title: this.instituteTool.extra_information_title,
                extra_information: this.instituteTool.extra_information,
                support_title_1: this.instituteTool.support_title_1,
                support_email_1: this.instituteTool.support_email_1,
                support_title_2: this.instituteTool.support_title_2,
                support_email_2: this.instituteTool.support_email_2,
                manual_title_1: this.instituteTool.manual_title_1,
                manual_url_1: this.instituteTool.manual_url_1,
                manual_title_2: this.instituteTool.manual_title_2,
                manual_url_2: this.instituteTool.manual_url_2,
                video_title_1: this.instituteTool.video_title_1,
                video_url_1: this.instituteTool.video_url_1,
                video_title_2: this.instituteTool.video_title_2,
                video_url_2: this.instituteTool.video_url_2,
                status: this.instituteTool.status === 'prohibited' ? null : this.instituteTool.status,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.tool.update', this.tool).url(), {
                onError: this.showFirstFormError,
            });
        },

        /**
         * Publishes the tool for the institute.
         */
        unpublish() {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.unpublish-tool'))) {
                return;
            }

            this.form.post(route('information-manager.tool.unpublish', this.tool).url(), {
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

            this.form.post(route('information-manager.tool.publish', this.tool).url(), {
                onError: this.showFirstFormError,
            });
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
