<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.homepage-information.edit.heading')" />

        <PageCard>
            <form @submit.prevent="submit">
                <FormGroup>
                    <FormColumn>
                        <TextInput
                            v-model="form.homepage_title_en"
                            :label="trans('institute.homepage-information.attributes.title_en')"
                            :error="form.errors.homepage_title_en"
                        />

                        <TextInput
                            v-model="form.homepage_title_nl"
                            :label="trans('institute.homepage-information.attributes.title_nl')"
                            :error="form.errors.homepage_title_nl"
                        />

                        <RichText
                            v-model="form.homepage_body_en"
                            :label="trans('institute.homepage-information.attributes.body_en')"
                            :error="form.errors.homepage_body_en"
                        />

                        <RichText
                            v-model="form.homepage_body_nl"
                            :label="trans('institute.homepage-information.attributes.body_nl')"
                            :error="form.errors.homepage_body_nl"
                        />

                        <Btn
                            type="submit"
                            variant="primary"
                            :disabled="form.processing"
                        >
                            {{ trans('action.store') }}
                        </Btn>
                    </FormColumn>
                </FormGroup>
            </form>
        </PageCard>
    </PageContainer>
</template>
<script>
import { useForm } from '@inertiajs/vue2';

import AdminLayout from '@/layouts/AdminLayout.vue';

import PageContainer from '@/components/page/PageContainer.vue';
import PageHeader from '@/components/page/PageHeader.vue';
import PageCard from '@/components/page/PageCard.vue';
import FormGroup from '@/components/FormGroup.vue';
import FormColumn from '@/components/FormColumn.vue';
import TextInput from '@/components/form/TextInput.vue';
import RichText from '@/components/form/RichText.vue';
import Btn from '@/components/Btn.vue';

export default {
    components: {
        Btn,
        RichText,
        TextInput,
        FormColumn,
        FormGroup,
        PageCard,
        PageContainer,
        PageHeader,
    },
    layout: AdminLayout,
    props: {
        institute: {
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
                homepage_title_en: this.institute.homepage_title_en,
                homepage_body_en: this.institute.homepage_body_en,
                homepage_title_nl: this.institute.homepage_title_nl,
                homepage_body_nl: this.institute.homepage_body_nl,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.put(route('information-manager.homepage-information.update'));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.homepage-information.edit.heading'),
        };
    },
};
</script>
