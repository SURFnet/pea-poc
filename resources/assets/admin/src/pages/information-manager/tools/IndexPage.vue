<template>
    <div class="container-xl flex-1 | page | space-y-6 | mt-6 sm:mt-16">
        <PageHeader :title="trans('tool.plural')">
            <Btn
                variant="primary"
                inertia
                :href="route('tool.index')"
            >
                {{ trans('action.add_more_tools') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="tools.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('information-manager.tool.index')"
            filter-data-key="tools"
            :sort-options="selectFromArray(sortOptions)"
            scrollable
        >
            <template #name="{ item }">
                <div class="flex flex-row items-center gap-4">
                    <EntityIcon
                        size="md"
                        :text="item.name"
                        :image="item.logo_url"
                    />

                    <span
                        class="font-semibold text-blue-500"
                        v-text="item.name"
                    />
                </div>
            </template>

            <template #description_short_stripped_tags="{ item }">
                <div class="flex flew-row items-center">
                    <span
                        class="text-gray-900 font-source-sans line-clamp-1"
                        v-text="item.description_short_stripped_tags"
                    />
                </div>
            </template>

            <template #categories="{ item }">
                <ExpandableTagList :item-list="item.institute.categories" />
            </template>

            <template #has_concept="{ item }">
                {{ item.has_concept ? trans('tool.has_concept.yes') : trans('tool.has_concept.no') }}
            </template>

            <template #edit="{ item }">
                <div
                    v-if="item.permissions.update"
                    class="text-right"
                >
                    <BaseDropdown
                        variant="no-outline"
                        position="right"
                        absolute
                    >
                        <div class="flex flex-col">
                            <DropdownItem
                                :href="item.edit_url"
                                as="button"
                            >
                                {{ editButtonCaption(item) }}
                            </DropdownItem>

                            <template v-if="item.has_concept">
                                <DropdownItem
                                    :href="route('information-manager.tool.publish-concept', item)"
                                    method="put"
                                    as="button"
                                >
                                    {{ trans('action.publish_concept') }}
                                </DropdownItem>

                                <DropdownItem
                                    :href="route('information-manager.tool.discard-concept', item)"
                                    method="put"
                                    as="button"
                                >
                                    {{ trans('action.discard_concept') }}
                                </DropdownItem>
                            </template>
                        </div>
                    </BaseDropdown>
                </div>
            </template>

            <template #status="{ item }">
                <ToolStatus
                    :status="item.institute.status"
                    :text="item.institute.status_display"
                />
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
import ExpandableTagList from '@/components/ExpandableTagList';
import ToolStatus from '@/components/ToolStatus';
import DropdownItem from '@/components/DropdownItem.vue';
import BaseDropdown from '@/components/BaseDropdown.vue';
import Btn from '@/components/Btn.vue';

export default {
    components: {
        Btn,
        BaseDropdown,
        DropdownItem,
        ToolStatus,
        PageHeader,
        DataTable,
        InertiaPagination,
        EntityIcon,
        ExpandableTagList,
    },
    layout: Layout,
    props: {
        tools: {
            type: Object,
            default: null,
        },
        categories: {
            type: [Object, Array],
            required: true,
        },
        statusOptions: {
            type: Object,
            required: true,
        },
        sortOptions: {
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
                    wrap: true,
                },
                {
                    key: 'description_short_stripped_tags',
                    value: trans('tool.attributes.description_short'),
                    filter: true,
                    filterKey: 'description_short',
                    wrap: true,
                    additionalColumnClass: 'hidden @3xl/data-table:table-cell',
                },
                {
                    key: 'categories',
                    value: trans('institute.tool.attributes.category'),
                    filter: true,
                    filterKey: 'category',
                    filterOptions: selectFromArray(this.categories),
                },
                {
                    key: 'status',
                    value: trans('tool.status'),
                    filter: true,
                    filterKey: 'status',
                    filterOptions: selectFromArray(this.statusOptions),
                },
                {
                    key: 'total_experiences',
                    value: trans('tool.total_experiences'),
                    filter: false,
                    additionalColumnClass: 'hidden @2xl/data-table:table-cell',
                },
                {
                    key: 'has_concept',
                    value: trans('tool.attributes.has_concept'),
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
    methods: {
        selectFromArray,
        /**
         * Determine caption of Edit Button
         *
         * @param {object} item
         * @returns {string}
         */
        editButtonCaption(item) {
            if (item.has_concept) {
                return trans('action.edit_concept');
            }

            return trans('action.edit');
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.tool.index.title'),
        };
    },
};
</script>
