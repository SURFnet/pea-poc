<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.category.edit.heading', { name: category.name })" />

        <PageCard>
            <form @submit.prevent="submit">
                <CategoryForm :form.sync="form" />

                <FormFooter align="end" class="mt-6">
                    <Btn
                        inertia
                        variant="default-dark"
                        class="mr-4"
                        :href="route('information-manager.category.index')"
                        v-text="trans('action.cancel')"
                    />

                    <Btn type="submit" variant="primary" :disabled="form.processing">
                        {{ trans('action.store') }}
                    </Btn>
                </FormFooter>
            </form>
        </PageCard>
    </PageContainer>
</template>

<script>
import Layout from '@/layouts/AdminLayout';

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';
import PageCard from '@/components/page/PageCard';

import Btn from '@/components/Btn';
import FormFooter from '@/components/FormFooter';
import CategoryForm from '@/pages/information-manager/category/components/CategoryForm';

export default {
    components: {
        PageContainer,
        PageHeader,
        PageCard,
        Btn,
        FormFooter,
        CategoryForm,
    },
    layout: Layout,
    props: {
        category: {
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
                name: this.category.name,
                description: this.category.description,
            }),
        };
    },
    methods: {
        /**
         * Submit the form.
         */
        submit() {
            this.form.post(route('information-manager.category.update', this.category).url());
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.category.edit.title', { name: this.category.name }),
        };
    },
};
</script>
