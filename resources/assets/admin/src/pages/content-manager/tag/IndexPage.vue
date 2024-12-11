<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tag.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('content-manager.tag.create')"
            >
                {{ trans('action.create') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="tags.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('content-manager.tag.index')"
            filter-data-key="tags"
            scrollable
        >
            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        inertia
                        variant="no-outline"
                        :href="route('content-manager.tag.edit', item)"
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

import { selectFromArray } from '@/helpers/input';

import Layout from '@/layouts/DefaultLayout';

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
        typesSelect: {
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
                    key: 'type_display',
                    value: trans('tag.attributes.type'),
                    filter: true,
                    filterKey: 'type',
                    filterOptions: selectFromArray(this.typesSelect),
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

            router.delete(route('content-manager.tag.destroy', tag));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.content-manager.tag.index.title'),
        };
    },
};
</script>
