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

            <template #is_custom="{ item }">
                {{ item.is_custom ? trans('tool.has_concept.yes') : trans('tool.has_concept.no') }}
            </template>

            <template #action="{ item }">
                <div
                    v-if="showDropdown(item)"
                    class="text-right"
                >
                    <BaseDropdown
                        variant="no-outline"
                        position="right"
                        absolute
                    >
                        <div class="flex flex-col">
                            <DropdownItem
                                v-if="item.is_custom && item.permissions.view_custom_tool"
                                :href="route('content-manager.tool.show', item)"
                                as="button"
                            >
                                {{ trans('action.view') }}
                            </DropdownItem>

                            <DropdownItem
                                v-if="item.permissions.convert"
                                :as-button="true"
                                @click="openConfirmDialog(item)"
                            >
                                {{ trans('action.convert-to-generic') }}
                            </DropdownItem>

                            <template v-if="item.permissions.update">
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
                            </template>
                        </div>
                    </BaseDropdown>
                </div>
            </template>
        </DataTable>

        <InertiaPagination :pagination="tools.pagination" />

        <ConfirmDialog
            :open="modelIsOpen"
            :text="trans('confirm.convert-to-generic', { item: currentTool?.name })"
            @closed="modelIsOpen = false"
            @confirmed="handleConfirmed"
        />
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
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { router } from '@inertiajs/vue2';

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
        ConfirmDialog,
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
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return { modelIsOpen: false, currentTool: null };
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
                    key: 'is_custom',
                    value: trans('tool.attributes.is_custom'),
                    filter: true,
                    filterKey: 'is_custom',
                    filterOptions: [
                        {
                            label: trans('tool.is_custom.yes'),
                            value: 'true',
                        },
                        {
                            label: trans('tool.is_custom.no'),
                            value: 'false',
                        },
                    ],
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
        /**
         * Open confirm dialog
         *
         * @param {object} item
         */
        openConfirmDialog(item) {
            this.modelIsOpen = true;
            this.currentTool = item;
        },
        /**
         * Handle confirm button
         */
        handleConfirmed() {
            router.post(route('content-manager.tool.convert', this.currentTool));
        },
        /**
         * Indicate if the dropdown should be shown
         *
         * @param {object} item
         *
         * @returns {boolean}
         */
        showDropdown(item) {
            return (
                item.permissions.update ||
                item.permissions.convert ||
                (item.permissions.view_custom_tool && item.is_custom)
            );
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
