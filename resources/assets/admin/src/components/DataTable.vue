<template>
    <div class="flex flex-col relative">
        <div
            v-if="sortOptions.length > 0"
            class="place-self-end mb-5"
        >
            <SelectInput
                ref="data_classification"
                v-model="sort"
                class="w-80 text-black"
                :label="trans('page.sort')"
                :options="sortOptions"
            />
        </div>

        <div class="-my-2 sm:-mx-6 lg:-mx-8">
            <div class="align-middle inline-block | min-w-full | py-2 sm:px-6 lg:px-8">
                <div class="@container/data-table">
                    <div
                        class="shadow border-b border-gray-200 rounded-lg"
                        :class="scrollable ? 'overflow-auto' : 'overflow-hidden'"
                    >
                        <table class="min-w-full | divide-y divide-gray-200">
                            <thead
                                v-if="columns"
                                class="bg-gray-50"
                            >
                                <tr>
                                    <th
                                        v-for="column in columns"
                                        :key="column.key"
                                        scope="col"
                                        :class="[headerClass(column), column.additionalColumnClass].join(' | ')"
                                        v-text="column.value"
                                    />
                                </tr>
                            </thead>

                            <thead
                                v-if="hasAnyFilter"
                                class="bg-gray-50"
                            >
                                <tr>
                                    <template v-for="column in columns">
                                        <FilterColumn
                                            v-if="column.filter && column.filterKey"
                                            :key="column.filterKey"
                                            v-model="localFilter[column.filterKey]"
                                            :options="column.filterOptions"
                                        />
                                    </template>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                <template v-if="items.length > 0">
                                    <tr
                                        v-for="(item, itemIndex) in items"
                                        :key="itemIndex"
                                    >
                                        <td
                                            v-for="(column, columnIndex) in columns"
                                            :key="`row-${itemIndex}-column-${columnIndex}`"
                                            class="text-sm font-light text-gray-900 | px-4 @4xl/data-table:px-6 py-4"
                                            :class="{
                                                'whitespace-nowrap': !column.wrap,
                                                [column.additionalColumnClass]: column.additionalColumnClass,
                                            }"
                                        >
                                            <slot
                                                :name="column.key"
                                                :item="item"
                                            >
                                                {{ getColumnValue(item, column.key) }}
                                            </slot>
                                        </td>
                                    </tr>
                                </template>

                                <template v-else>
                                    <tr>
                                        <td
                                            :colspan="columns.length"
                                            class="whitespace-nowrap text-center text-sm font-light text-gray-800 italic | px-4 @4xl/data-table:px-6 py-4"
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
    </div>
</template>

<script>
import { router } from '@inertiajs/vue2';
import get from 'lodash/get';

import FilterColumn from '@/components/table/FilterColumn';
import SelectInput from '@/components/form/SelectInput.vue';

export default {
    components: {
        SelectInput,
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
        scrollable: {
            type: Boolean,
            default: false,
        },
        sortOptions: {
            type: Array,
            default: () => [],
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
            sort: this.$page.props.initialSort ?? '',
        };
    },
    computed: {
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

                router.visit(`${this.filterUrl}?${params.toString()}`, {
                    preserveScroll: true,
                    preserveState: true,
                    only: [this.filterDataKey],
                });
            },
            deep: true,
        },
        sort: {
            /**
             * Handles the change of the sorting.
             */
            handler() {
                const params = new URLSearchParams(window.location.search);

                params.set('sort', this.sort ?? '');
                params.set('page', 1);

                router.visit(`${this.filterUrl}?${params.toString()}`, {
                    preserveScroll: true,
                    preserveState: true,
                    only: [this.filterDataKey, this.sort],
                });
            },
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
                return 'text-left text-xs font-medium uppercase @4xl/data-table:tracking-wider text-gray-500 | px-4 @4xl/data-table:px-6 py-3';
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
