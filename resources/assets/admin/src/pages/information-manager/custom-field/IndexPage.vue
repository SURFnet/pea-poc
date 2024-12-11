<template>
    <PageContainer>
        <PageHeader :title="trans('page.information-manager.custom-field.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('information-manager.custom-field.create')"
            >
                {{ trans('action.create') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="customFields.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('information-manager.custom-field.index')"
            filter-data-key="customFields"
            scrollable
        >
            <template #title="{ item }">
                <div
                    class="line-clamp-1"
                    v-text="item.title"
                />
            </template>

            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        inertia
                        variant="no-outline"
                        :href="route('information-manager.custom-field.edit', item)"
                    >
                        {{ trans('action.edit') }}
                    </Btn>

                    <Btn
                        variant="no-outline"
                        @click="deleteCustomField(item)"
                    >
                        {{ trans('action.delete') }}
                    </Btn>
                </div>
            </template>
        </DataTable>

        <InertiaPagination :pagination="customFields.pagination" />
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

import { selectFromArray } from '@/helpers/input';

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
        customFields: {
            type: Object,
            default: null,
        },
        tabTypes: {
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
                    key: 'title',
                    value: trans('custom-field.attributes.title'),
                    filter: true,
                    filterKey: 'title',
                    wrap: true,
                },
                {
                    key: 'tab_type_display',
                    value: trans('custom-field.attributes.tab_type'),
                    filter: true,
                    filterKey: 'tab_type',
                    wrap: true,
                    filterOptions: selectFromArray(this.tabTypes),
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
         * Delete custom field
         *
         * @param {object} customField
         */
        deleteCustomField(customField) {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.delete-entity', {entity: customField.title}))) {
                return;
            }

            router.delete(route('information-manager.custom-field.destroy', customField));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.information-manager.custom-field.index.title'),
        };
    },
};
</script>
