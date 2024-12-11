<template>
    <PageContainer>
        <PageHeader :title="trans('page.admin.institutes.index.heading')" />

        <DataTable
            :columns="tableColumns"
            :items="institutes"
            :empty-text="trans('message.no-data')"
            scrollable
        >
            <template #action="{ item }">
                <div class="text-right">
                    <Btn
                        variant="primary"
                        @click.prevent="loginAsInstitute(item)"
                    >
                        {{ trans('action.login') }}
                    </Btn>
                </div>
            </template>
        </DataTable>
    </PageContainer>
</template>
<script>
import { router } from '@inertiajs/vue2';

import Layout from '@/layouts/DefaultLayout';

import Btn from '@/components/Btn.vue';
import PageContainer from '@/components/page/PageContainer.vue';
import PageHeader from '@/components/page/PageHeader.vue';
import DataTable from '@/components/DataTable.vue';

export default {
    components: {
        PageContainer,
        PageHeader,
        DataTable,
        Btn,
    },
    layout: Layout,
    props: {
        institutes: {
            type: Array,
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
                    key: 'short_name',
                    value: trans('institute.attributes.short_name'),
                    filter: false,
                    wrap: true,
                },
                {
                    key: 'full_name',
                    value: trans('institute.attributes.full_name'),
                    filter: false,
                    wrap: true,
                },
                {
                    key: 'domain',
                    value: trans('institute.attributes.domain'),
                    filter: false,
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
         * Login as
         *
         * @param {object} institute
         */
        loginAsInstitute(institute) {
            router.post(route('admin.institutes.impersonate', institute));
        },
    },
    /**
     * The reactive metainfo object
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.admin.institutes.index.title'),
        };
    },
};
</script>
