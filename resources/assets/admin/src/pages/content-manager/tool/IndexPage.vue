<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('content-manager.tool.create')"
                v-text="trans('action.create')"
            />
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="tools.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('content-manager.tool.index')"
            filter-data-key="tools"
        >
            <template #name="{ item }">
                <div class="flex flew-row items-center">
                    <EntityIcon size="md" :text="item.name" :image="item.image_url" class="mr-4" />
                    <span class="font-semibold" v-text="item.name" />
                </div>
            </template>
            <template #status_display="{ item }">
                <StatusPill :variant="pillVariant(item.status)" v-text="item.status_display" />
            </template>
            <template #features="{ item }">
                <ExpandableTagList :item-list="item.features" />
            </template>
            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        v-if="item.permissions.update"
                        inertia
                        variant="no-outline"
                        :href="route('content-manager.tool.edit', item)"
                    >
                        <FontAwesomeIcon icon="pencil-alt" class="text-lg text-gray-500 hover:text-gray-700" />
                    </Btn>
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

import DataTable from '@/components/DataTable';
import InertiaPagination from '@/components/InertiaPagination';
import EntityIcon from '@/components/EntityIcon';
import Btn from '@/components/Btn';
import StatusPill from '@/components/StatusPill';
import ExpandableTagList from '@/components/ExpandableTagList';

export default {
    components: {
        PageContainer,
        PageHeader,
        DataTable,
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
                    key: 'status_display',
                    value: trans('tool.status'),
                    filter: true,
                    filterKey: 'status',
                    filterOptions: selectFromArray(this.statusOptions),
                },
                {
                    key: 'features',
                    value: trans('feature.plural'),
                    filter: true,
                    filterKey: 'feature',
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
