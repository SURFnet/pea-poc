<template>
    <aside
        class="lg:col-span-3 lg:pt-8 lg:-ml-8 lg:-mb-12 lg:px-8 | bg-gray-50 border-r border-gray-300 | space-y-4 py-6 px-2 sm:px-6 lg:py-0"
    >
        <FilterList
            v-for="(tagsForType, tagType) in $page.props.sidebar.tags"
            :key="tagType"
            :title="trans(`tag.tag_types.${tagType}`)"
        >
            <TagInput
                key-for-value="slug"
                :value="toolFilterStore.tagTypesWithSlugs[tagType]"
                :available-tags="tagsForType"
                :placeholder="trans('tag.filter.placeholder')"
                :select-label="trans('tag.filter.select_label')"
                :selected-label="trans('tag.filter.selected_label')"
                @input="handleTagFilterChange($event, tagType)"
            />
        </FilterList>
    </aside>
</template>

<script>
import { router } from '@inertiajs/vue2';
import { mapStores } from 'pinia';

import FilterList from '@/components/filter/FilterList';
import TagInput from '@/components/form/TagInput.vue';

import { useToolFilterStore } from '@/stores/tool-filter';

import { getFilterUrlByFilters } from '@/helpers/tool-filter-url';

export default {
    components: {
        TagInput,
        FilterList,
    },
    computed: {
        ...mapStores(useToolFilterStore),
    },
    methods: {
        /**
         * Handles changes in filters
         *
         * @param {Array} slugs
         * @param {string} type
         */
        handleTagFilterChange(slugs, type) {
            // eslint-disable-next-line vue/no-undef-properties
            this.toolFilterStore.updateTagTypesWithSlugs(type, slugs);

            const filterUrl = getFilterUrlByFilters(
                this.toolFilterStore.searchTerm,
                this.toolFilterStore.tagTypesWithSlugs
            );

            router.visit(filterUrl, {
                preserveScroll: true,
            });
        },
    },
};
</script>
