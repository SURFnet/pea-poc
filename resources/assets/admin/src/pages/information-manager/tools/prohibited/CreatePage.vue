<template>
    <div>
        <ToolHeader :tool="tool" :back-url="backUrl" />

        <PageContainer>
            <PageCard>
                <form @submit.prevent="submit">
                    <ToolForm
                        :form.sync="form"
                        :alternative-tools="alternativeTools"
                        :change-to-fit-url="route('information-manager.tool.create', { tool: tool.id })"
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
import Layout from '@/layouts/DefaultLayout';

import PageContainer from '@/components/page/PageContainer';
import PageCard from '@/components/page/PageCard';
import ToolHeader from '@/pages/other/tool/components/ToolHeader';

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
                why_unfit: null,
                alternative_tool_id: null,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.tool.prohibited.store', { tool: this.tool }).url());
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
