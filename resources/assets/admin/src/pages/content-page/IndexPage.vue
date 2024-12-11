<template>
    <PageContainer>
        <PageHeader :title="trans('content-page.index.heading')">
            <Btn
                variant="primary"
                inertia
                :href="route('content-page.create')"
            >
                {{ trans('action.create') }}
            </Btn>
        </PageHeader>

        <DataTable
            :columns="tableColumns"
            :items="contentPages.data"
            :empty-text="trans('message.no-data')"
            :filter-url="route('content-page.index')"
            filter-data-key="customFields"
            scrollable
        >
            <template #title="{ item }">
                <div
                    class="line-clamp-1"
                    v-text="item.title"
                />
            </template>

            <template #slug="{ item }">
                <a
                    :href="route('content-page.show', item)"
                    v-text="`/page/${item.slug}`"
                />
            </template>

            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        inertia
                        variant="no-outline"
                        :href="route('content-page.edit', item)"
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

        <InertiaPagination :pagination="contentPages.pagination" />
    </PageContainer>
</template>

<script>
import { router } from '@inertiajs/vue2';

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
        contentPages: {
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
                    key: 'title_en',
                    value: trans('content-page.attributes.title_en'),
                    filter: true,
                    filterKey: 'title_en',
                    wrap: true,
                },
                {
                    key: 'title_nl',
                    value: trans('content-page.attributes.title_nl'),
                    filter: true,
                    filterKey: 'title_nl',
                    wrap: true,
                },
                {
                    key: 'slug',
                    value: trans('content-page.attributes.url'),
                    filter: true,
                    filterKey: 'slug',
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
         * Delete custom field
         *
         * @param {object} contentPage
         */
        deleteCustomField(contentPage) {
            // eslint-disable-next-line no-alert
            if (!window.confirm(trans('confirm.delete-entity', { entity: contentPage.title }))) {
                return;
            }

            router.delete(route('content-page.destroy', contentPage));
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('content-page.index.title'),
        };
    },
};
</script>
