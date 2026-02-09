<template>
    <div class="container-xl flex-1 | page | space-y-6 | mt-6 sm:mt-16">
        <PageHeader :title="trans('tool.custom_tool.plural')">
            <Btn
                variant="primary"
                inertia
                :href="route('information-manager.custom-tool.create')"
            >
                {{ trans('action.create') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="tools.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('information-manager.custom-tool.index')"
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

            <template #has_tool_concept="{ item }">
                {{ item.has_tool_concept ? trans('tool.has_concept.yes') : trans('tool.has_concept.no') }}
            </template>

            <template #has_institute_tool_concept="{ item }">
                {{ item.has_institute_tool_concept ? trans('tool.has_concept.yes') : trans('tool.has_concept.no') }}
            </template>

            <template #institute_tool_status="{ item }">
                <ToolStatus
                    :status="item.institute_tool_status"
                    :text="item.institute_tool_status_display ?? '-'"
                />
            </template>

            <template #status="{ item }">
                <StatusPill :variant="pillVariant(item.status)">
                    {{ item.status_display }}
                </StatusPill>
            </template>

            <template #edit="{ item }">
                <div
                    v-if="Object.values(item.abilities).includes(true)"
                    class="text-right"
                >
                    <BaseDropdown
                        variant="no-outline"
                        position="right"
                        absolute
                    >
                        <div class="flex flex-col">
                            <template v-if="getAvailableActions(item).tool">
                                <div class="px-4 py-2 text-left font-semibold">
                                    {{ trans('tool.singular') }}
                                </div>

                                <DropdownItem
                                    v-if="item.abilities.update"
                                    :href="item.edit_url"
                                    as="button"
                                >
                                    {{ editButtonCaption(item) }}
                                </DropdownItem>

                                <DropdownItem
                                    v-if="item.abilities.publish_concept"
                                    :href="route('information-manager.custom-tool.publish-concept', item)"
                                    method="put"
                                    as="button"
                                >
                                    {{ trans('action.publish_concept') }}
                                </DropdownItem>

                                <DropdownItem
                                    v-if="item.abilities.discard_concept"
                                    :href="route('information-manager.custom-tool.discard-concept', item)"
                                    method="put"
                                    as="button"
                                >
                                    {{ trans('action.discard_concept') }}
                                </DropdownItem>

                                <DropdownItem
                                    v-if="item.abilities.delete"
                                    as-button
                                    @click="deleteTool(item)"
                                >
                                    {{ trans('action.delete') }}
                                </DropdownItem>
                            </template>

                            <hr
                                v-if="getAvailableActions(item).tool && getAvailableActions(item).instituteTool"
                                class="mx-2 my-2 pt-0"
                            />

                            <template v-if="getAvailableActions(item).instituteTool">
                                <div class="px-4 py-2 text-left font-semibold">
                                    {{ trans('tool.institute_tool.singular') }}
                                </div>

                                <DropdownItem
                                    v-if="item.abilities.update_institute_tool"
                                    :href="item.institute_tool_edit_url"
                                    as="button"
                                >
                                    Edit
                                </DropdownItem>

                                <template v-if="item.has_institute_tool_concept">
                                    <DropdownItem
                                        :href="
                                            route('information-manager.custom-tool.information.publish-concept', item)
                                        "
                                        method="put"
                                        as="button"
                                    >
                                        {{ trans('action.publish_concept') }}
                                    </DropdownItem>

                                    <DropdownItem
                                        :href="
                                            route('information-manager.custom-tool.information.discard-concept', item)
                                        "
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
    </div>
</template>

<script>
import { selectFromArray } from '@/helpers/input';

import Layout from '@/layouts/AdminLayout';

import PageHeader from '@/components/page/PageHeader';

import DataTable from '@/components/DataTable';
import InertiaPagination from '@/components/InertiaPagination';
import EntityIcon from '@/components/EntityIcon';
import ToolStatus from '@/components/ToolStatus';
import DropdownItem from '@/components/DropdownItem.vue';
import BaseDropdown from '@/components/BaseDropdown.vue';
import Btn from '@/components/Btn.vue';
import StatusPill from '@/components/StatusPill.vue';
import { router } from '@inertiajs/vue2';

export default {
    components: {
        StatusPill,
        Btn,
        BaseDropdown,
        DropdownItem,
        ToolStatus,
        PageHeader,
        DataTable,
        InertiaPagination,
        EntityIcon,
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
                    key: 'has_tool_concept',
                    value: trans('tool.attributes.has_concept'),
                    filter: false,
                },
                {
                    key: 'institute_tool_status',
                    value: trans('tool.custom_tool.institute_tool_status'),
                    // filter: true,
                    // filterKey: 'status',
                    // filterOptions: selectFromArray(this.statusOptions),
                },
                {
                    key: 'has_institute_tool_concept',
                    value: trans('tool.custom_tool.has_institute_tool_concept'),
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
            if (item.has_tool_concept) {
                return trans('action.edit_concept');
            }

            return trans('action.edit');
        },
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
         *
         * @param {object} item
         *
         * @returns {{tool: boolean, instituteTool: boolean}}
         */
        getAvailableActions(item) {
            return {
                tool:
                    item.abilities.update ||
                    item.abilities.publish_concept ||
                    item.abilities.discard_concept ||
                    item.abilities.delete,
                instituteTool: item.abilities.update_institute_tool || item.has_institute_tool_concept,
            };
        },
        /**
         * Delete the tool.
         *
         * @param {object} item
         *
         * @returns {void}
         */
        deleteTool(item) {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.delete-entity', { entity: item.name }))) {
                return;
            }

            router.delete(route('information-manager.custom-tool.destroy', item));
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
