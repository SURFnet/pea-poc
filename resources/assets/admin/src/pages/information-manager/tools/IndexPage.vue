<template>
    <div class="container-xl | page | space-y-6 | mt-6 sm:mt-16">
        <PageHeader :title="trans('tool.plural')" />

        <DataTable
            :columns="tableColumns"
            :items="tools.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('information-manager.tool.index')"
            filter-data-key="tools"
        >
            <template #name="{ item }">
                <div class="flex flew-row items-center">
                    <EntityIcon size="md" :text="item.name" :image="item.image_url" class="mr-4" />
                    <span class="font-semibold text-blue-500" v-text="item.name" />
                </div>
            </template>
            <template #description="{ item }">
                <div class="flex flew-row items-center">
                    <span class="text-gray-900 font-source-sans" v-text="item.description_short" />
                </div>
            </template>
            <template #category="{ item }">
                <ExpandableTagList :item-list="item.categories" />
            </template>
            <template #rating="{ item }">
                <div class="flex flew-row items-center">
                    <StarRating :rating="item.rating" />
                </div>
            </template>
            <template #edit="{ item }">
                <div class="flex flew-row justify-center items-center">
                    <inertia-link :href="item.edit_url">
                        <FontAwesomeIcon icon="pencil-alt" class="text-lg text-gray-500 hover:text-gray-700" />
                    </inertia-link>
                </div>
            </template>
            <template #status="{ item }">
                <ToolStatus :status="item.institute.status" :text="item.institute.status_display" />
            </template>
        </DataTable>

        <InertiaPagination :pagination="tools.pagination" />
    </div>
</template>

<script>
import { selectFromArray } from '@/helpers/input';

import Layout from '@/layouts/AdminLayout';

import PageHeader from '@/components/page/PageHeader';

import DataTable from '@/components/DataTable';
import InertiaPagination from '@/components/InertiaPagination';
import EntityIcon from '@/components/EntityIcon';
import StarRating from '@/components/StarRating';
import ExpandableTagList from '@/components/ExpandableTagList';
import ToolStatus from '@/components/ToolStatus';

export default {
    components: {
        ToolStatus,
        PageHeader,
        DataTable,
        InertiaPagination,
        EntityIcon,
        StarRating,
        ExpandableTagList,
    },
    layout: Layout,
    props: {
        tools: {
            type: Object,
            default: null,
        },
        categoryOptions: {
            type: Object,
            required: true,
        },
        statusOptions: {
            type: Object,
            required: true,
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
                    value: trans('tool.attributes.name'),
                    filter: true,
                    filterKey: 'name',
                },
                {
                    key: 'description_short',
                    value: trans('tool.attributes.description_short'),
                    filter: true,
                    filterKey: 'description_short',
                },
                {
                    key: 'category',
                    value: trans('tool.attributes.category'),
                    filter: true,
                    filterKey: 'category',
                    filterOptions: selectFromArray(this.categoryOptions),
                },
                {
                    key: 'status',
                    value: trans('tool.status'),
                    filter: true,
                    filterKey: 'status',
                    filterOptions: selectFromArray(this.statusOptions),
                },
                {
                    key: 'rating',
                    value: trans('tool.rating'),
                    filter: false,
                },
                {
                    key: 'edit',
                    value: trans('action.edit'),
                    filter: false,
                },
            ];
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-manager.tool.index.title'),
        };
    },
};
</script>
