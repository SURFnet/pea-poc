<template>
    <div class="container-xl">
        <ToolHeader :tool="tool" :back-url="backUrl" />

        <PageContainer>
            <PageCard>
                <form @submit.prevent="submit">
                    <ToolForm
                        :form.sync="form"
                        :alternative-tools="alternativeTools"
                        :change-to-fit-url="route('information-manager.tool.edit', { tool: tool.id })"
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
import ToolForm from '@/pages/information-manager/tools/prohibited/components/ToolForm';

export default {
    components: {
        PageContainer,
        PageCard,
        ToolHeader,
        Btn,
        FormFooter,
        ToolForm,
    },
    layout: Layout,
    props: {
        backUrl: {
            type: String,
            required: true,
        },
        tool: {
            type: Object,
            required: true,
        },
        instituteTool: {
            type: Object,
            required: true,
        },
        alternativeTools: {
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
            form: this.$inertia.form({
                _method: 'put',
                why_unfit: this.instituteTool.why_unfit,
                alternative_tool_id: this.instituteTool.alternative_tool_id,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.tool.prohibited.update', this.tool).url());
        },

        /**
         * Publishes the tool for the institute.
         */
        unpublish() {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.unpublish-tool'))) {
                return;
            }

            this.form.post(route('information-manager.tool.prohibited.unpublish', this.tool).url());
        },

        /**
         * Publishes the tool for the institute.
         */
        publish() {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.publish-tool'))) {
                return;
            }

            this.form.post(route('information-manager.tool.prohibited.publish', this.tool).url());
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
