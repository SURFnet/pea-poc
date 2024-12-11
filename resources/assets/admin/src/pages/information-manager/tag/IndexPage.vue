<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.tag.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('information-manager.tag.create')"
            >
                {{ trans('action.create') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="tags.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('information-manager.tag.index')"
            filter-data-key="tags"
            scrollable
        >
            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        inertia
                        variant="no-outline"
                        :href="route('information-manager.tag.edit', item)"
                    >
                        {{ trans('action.edit') }}
                    </Btn>

                    <Btn
                        variant="no-outline"
                        @click="deleteTag(item)"
                    >
                        {{ trans('action.delete') }}
                    </Btn>
                </div>
            </template>
        </DataTable>

        <InertiaPagination :pagination="tags.pagination" />
    </PageContainer>
</template>

<script>
import { router } from '@inertiajs/vue2';

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
        tags: {
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
                    key: 'name_array.en',
                    value: trans('tag.attributes.name_en'),
                    filter: true,
                    filterKey: 'name_en',
                    wrap: true,
                },
                {
                    key: 'name_array.nl',
                    value: trans('tag.attributes.name_nl'),
                    filter: true,
                    filterKey: 'name_nl',
                    wrap: true,
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
         * @param {object} tag
         */
        deleteTag(tag) {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.delete-entity', {entity: tag.name}))) {
                return;
            }

            router.delete(route('information-manager.tag.destroy', tag));
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
