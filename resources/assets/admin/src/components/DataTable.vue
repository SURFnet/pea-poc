<template>
    <div class="flex flex-col relative">
        <div class="overflow-x-auto | -my-2 sm:-mx-6 lg:-mx-8">
            <div class="align-middle inline-block | min-w-full | py-2 sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 rounded-lg">
                    <table class="min-w-full | divide-y divide-gray-200">
                        <thead v-if="columns" class="bg-gray-50">
                            <tr>
                                <th
                                    v-for="column in columns"
                                    :key="column.key"
                                    scope="col"
                                    :class="headerClass(column)"
                                    v-text="column.value"
                                />
                            </tr>
                        </thead>
                        <thead v-if="hasAnyFilter" class="bg-gray-50">
                            <tr>
                                <template v-for="column in columns">
                                    <FilterColumn
                                        v-if="column.filter && column.filterKey"
                                        :key="column.filterKey"
                                        v-model="localFilter[column.filterKey]"
                                        :column="column.key"
                                        :options="column.filterOptions"
                                    />
                                </template>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template v-if="items.length > 0">
                                <tr v-for="(item, itemIndex) in items" :key="itemIndex">
                                    <td
                                        v-for="(column, columnIndex) in rowColumns"
                                        :key="`row-${itemIndex}-column-${columnIndex}`"
                                        class="whitespace-nowrap text-sm font-light text-gray-900 | px-6 py-4"
                                    >
                                        <slot :name="column" :item="item">
                                            {{ getColumnValue(item, column) }}
                                        </slot>
                                    </td>
                                </tr>
                            </template>

                            <template v-else>
                                <tr>
                                    <td
                                        :colspan="rowColumns.length"
                                        class="whitespace-nowrap text-center text-sm font-light text-gray-800 italic | px-6 py-4"
                                        v-text="emptyText"
                                    />
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { get } from 'lodash';

import FilterColumn from '@/components/table/FilterColumn';

export default {
    components: {
        FilterColumn,
    },
    props: {
        columns: {
            type: Array,
            default: null,
        },
        items: {
            type: Array,
            default: null,
        },
        emptyText: {
            type: String,
            default: null,
        },
        filterUrl: {
            type: String,
            default: null,
        },
        filterDataKey: {
            type: String,
            default: null,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            localFilter: this.$page.props.initialFilter ?? {},
        };
    },
    computed: {
        /**
         * Determines the columns.
         *
         * @returns {Array}
         */
        rowColumns() {
            return this.columns.map((column) => column.key);
        },
        /**
         * Determines if there is any filter.
         *
         * @returns {boolean}
         */
        hasAnyFilter() {
            return this.columns.some((column) => column.filter);
        },
    },
    watch: {
        localFilter: {
            /**
             * Handles the change of the filters.
             */
            handler() {
                const params = new URLSearchParams(window.location.search);

                Object.entries(this.localFilter).forEach((entry) => {
                    const [key, value] = entry;

                    params.set(`filter[${key}]`, value);
                });

                params.set('page', 1);

                this.$inertia.visit(`${this.filterUrl}?${params.toString()}`, {
                    preserveScroll: true,
                    preserveState: true,
                    only: [this.filterDataKey],
                });
            },
            deep: true,
        },
    },
    methods: {
        /**
         * Determines the classes for the header.
         *
         * @param {object} column
         *
         * @returns {string}
         */
        headerClass(column) {
            if (!column.headerClass) {
                return 'text-left text-xs font-medium uppercase tracking-wider text-gray-500 | px-6 py-3';
            }

            return column.headerClass;
        },
        /**
         * @param {object} item
         * @param {string} column
         *
         * @returns {*}
         */
        getColumnValue: (item, column) => get(item, column),
    },
};
</script>
