<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('content-manager.tool.create')"
            >
                {{ trans('action.create') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="tools.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('content-manager.tool.index')"
            filter-data-key="tools"
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
                        class="font-semibold"
                        v-text="item.name"
                    />
                </div>
            </template>

            <template #features="{ item }">
                <ExpandableTagList :item-list="item.features" />
            </template>

            <template #status_display="{ item }">
                <StatusPill :variant="pillVariant(item.status)">
                    {{ item.status_display }}
                </StatusPill>
            </template>

            <template #has_concept="{ item }">
                {{ item.has_concept ? trans('tool.has_concept.yes') : trans('tool.has_concept.no') }}
            </template>

            <template #action="{ item }">
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
                                :href="route('content-manager.tool.edit', item)"
                                as="button"
                            >
                                {{ editButtonCaption(item) }}
                            </DropdownItem>

                            <template v-if="item.has_concept">
                                <DropdownItem
                                    :href="route('content-manager.tool.publish-concept', item)"
                                    method="put"
                                    as="button"
                                >
                                    {{ trans('action.publish_concept') }}
                                </DropdownItem>

                                <DropdownItem
                                    :href="route('content-manager.tool.discard-concept', item)"
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
        </DataTable>

        <InertiaPagination :pagination="tools.pagination" />
    </PageContainer>
</template>

<script>
import { selectFromArray } from '@/helpers/input';

import Layout from '@/layouts/DefaultLayout';

import PageContainer from '@/components/page/PageContainer';
import PageHeader from '@/components/page/PageHeader';

import BaseDropdown from '@/components/BaseDropdown';
import DataTable from '@/components/DataTable';
import DropdownItem from '@/components/DropdownItem';
import InertiaPagination from '@/components/InertiaPagination';
import EntityIcon from '@/components/EntityIcon';
import Btn from '@/components/Btn';
import StatusPill from '@/components/StatusPill';
import ExpandableTagList from '@/components/ExpandableTagList';

export default {
    components: {
        PageContainer,
        PageHeader,
        BaseDropdown,
        DataTable,
        DropdownItem,
        InertiaPagination,
        EntityIcon,
        Btn,
        StatusPill,
        ExpandableTagList,
    },
    layout: Layout,
    props: {
        tools: {
            type: Object,
            default: null,
        },
        features: {
            type: [Object, Array],
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
                    wrap: true,
                },
                {
                    key: 'features',
                    value: trans('tool.attributes.features'),
                    filter: true,
                    filterKey: 'feature',
                    filterOptions: selectFromArray(this.features),
                },
                {
                    key: 'status_display',
                    value: trans('tool.status'),
                    filter: true,
                    filterKey: 'status',
                    filterOptions: selectFromArray(this.statusOptions),
                },
                {
                    key: 'has_concept',
                    value: trans('tool.attributes.has_concept'),
                    filter: false,
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
         * Determines the color of the pill.
         *
         * @param {string} status
         *
         * @returns {string}
         */
        pillVariant(status) {
            if (status === 'concept') {
                return 'inactive';
            }

            return 'success';
        },

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
            title: trans('page.content-manager.tool.index.title'),
        };
    },
};
</script>
