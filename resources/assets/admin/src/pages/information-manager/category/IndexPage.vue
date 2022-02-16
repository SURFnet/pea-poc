<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.category.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('information-manager.category.create')"
                v-text="trans('action.create')"
            />
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="categories.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('information-manager.category.index')"
            filter-data-key="categories"
        >
            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        inertia
                        variant="no-outline"
                        :href="route('information-manager.category.edit', item)"
                        v-text="trans('action.edit')"
                    />

                    <Btn variant="no-outline" @click="deleteCategory(item)" v-text="trans('action.delete')" />
                </div>
            </template>
        </DataTable>

        <InertiaPagination :pagination="categories.pagination" />
    </PageContainer>
</template>

<script>
import Layout from '@/layouts/AdminLayout';

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';

import DataTable from '@/components/DataTable';
import InertiaPagination from '@/components/InertiaPagination';
import Btn from '@/components/Btn';

export default {
    components: {
        PageContainer,
        PageHeader,
        DataTable,
        InertiaPagination,
        Btn,
    },
    layout: Layout,
    props: {
        categories: {
            type: Object,
            default: null,
        },
    },
    computed: {
        /**
         * Defines the columns for the table.
         *
         * @returns {Array}
         */
        tableColumns() {
            return [
                {
                    key: 'name',
                    value: trans('category.attributes.name'),
                    filter: true,
                    filterKey: 'name',
                },
                {
                    key: 'description_truncated',
                    value: trans('category.attributes.description'),
                    filter: true,
                    filterKey: 'description',
                },
                {
                    key: 'action',
                    value: '',
                    headerClass: 'relative px-6 py-3',
                },
            ];
        },
    },
    methods: {
        /**
         * Delete category
         *
         * @param {object} category
         */
        deleteCategory(category) {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.delete-entity', {entity: category.name}))) {
                return;
            }

            this.$inertia.delete(route('information-manager.category.destroy', category));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.category.index.title'),
        };
    },
};
</script>
