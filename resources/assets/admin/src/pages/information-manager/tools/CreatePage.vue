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
                        :change-to-prohibited-url="
                            route('information-manager.tool.prohibited.create', { tool: tool.id })
                        "
                    />

                    <FormFooter align="end" class="mt-6">
                        <Btn
                            inertia
                            class="mr-4"
                            variant="default-dark"
                            :href="backUrl"
                            v-text="trans('action.cancel')"
                        />

                        <Btn type="submit" variant="primary" :disabled="form.processing">
                            {{ trans('action.add') }}
                        </Btn>
                    </FormFooter>
                </form>
            </PageCard>
        </PageContainer>
    </div>
</template>

<script>
import FormErrorsMixin from '@/mixins/form-errors.js';
import Layout from '@/layouts/AdminLayout';

import PageContainer from '@/components/page/PageContainer';
import PageCard from '@/components/page/PageCard';
import ToolHeader from '@/pages/our/tool/components/ToolHeader';

import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import ToolForm from '@/pages/information-manager/tools/components/ToolForm';

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
        categories: {
            type: Object,
            required: true,
        },
        institute: {
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
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            form: this.$inertia.form({
                categories: [],
                description_1: null,
                description_1_image_filename: null,
                description_1_image_url: null,
                description_2: null,
                description_2_image_filename: null,
                description_2_image_url: null,
                extra_information_title: null,
                extra_information: null,
                support_title_1: null,
                support_email_1: null,
                support_title_2: null,
                support_email_2: null,
                manual_title_1: null,
                manual_url_1: null,
                manual_title_2: null,
                manual_url_2: null,
                video_title_1: null,
                video_url_1: null,
                video_title_2: null,
                video_url_2: null,
                status: null,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.tool.store', { tool: this.tool }).url(), {
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
            title: trans('page.information-manager.tool.create.title', { tool: this.tool.name }),
        };
    },
};
</script>
