<template>
    <aside
        class="lg:col-span-3 lg:pt-8 lg:-ml-8 lg:-mb-12 lg:pl-8 | bg-gray-50 border-r border-gray-300 | space-y-4 py-6 px-2 sm:px-6 lg:py-0 lg:px-0"
    >
        <FilterList v-if="$page.props.sidebar.categories" :title="trans('category.plural')">
            <FilterItem
                v-for="category in $page.props.sidebar.categories"
                :key="category.id"
                type="category"
                :item="category"
            >
                <input
                    :id="`filter-category-${category.id}`"
                    v-model="filter.categories"
                    type="checkbox"
                    :value="category.id"
                    class="h-4 w-4 | rounded text-blue-600 border-gray-300 | focus:ring-blue-400 | mr-2"
                    @change="updateFilter()"
                />
            </FilterItem>
        </FilterList>

        <FilterList v-if="$page.props.sidebar.features" :title="trans('feature.plural')">
            <FilterItem
                v-for="feature in $page.props.sidebar.features"
                :key="feature.id"
                type="feature"
                :item="feature"
            >
                <input
                    :id="`filter-feature-${feature.id}`"
                    v-model="filter.features"
                    type="checkbox"
                    :value="feature.id"
                    class="h-4 w-4 | rounded text-blue-600 border-gray-300 | focus:ring-blue-400 | mr-2"
                    @change="updateFilter()"
                />
            </FilterItem>
        </FilterList>
    </aside>
</template>

<script>
import FilterList from '@/components/filter/FilterList';
import FilterItem from '@/components/filter/FilterItem';

export default {
    components: {
        FilterList,
        FilterItem,
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            filter: {
                features: [],
                categories: [],
            },
        };
    },
    /**
     * Preset filters from query string
     */
    mounted() {
        const params = new URLSearchParams(window.location.search);

        this.filter.features = params.getAll('filter[features][]');
        this.filter.categories = params.getAll('filter[categories][]');
    },
    methods: {
        /**
         * Handles the logic to apply a filter.
         */
        updateFilter() {
            let searchParam = '';
            const params = new URLSearchParams(window.location.search);

            if (params.has('search')) {
                searchParam = params.get('search');
            }

            this.$inertia.get(route(route().current()), {
                filter: this.filter,
                search: searchParam,
            });
        },
    },
};
</script>
