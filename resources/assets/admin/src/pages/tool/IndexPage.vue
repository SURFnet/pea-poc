<template>
    <Layout>
        <div class="relative | space-y-6">
            <BannerHeader
                page="tools"
                :title="$page.props.currentUser.institute.full_name"
                :image="$page.props.currentUser.institute.banner_url"
                :search-placeholder="trans('page.shared.section-header.search-tools')"
            />

            <div
                v-if="activeFilters.length > 0"
                class="flex flex-row flex-wrap gap-2 items-baseline"
            >
                <span>{{ trans('page.our.tool.index.current_filters') }}: </span>

                <TagPill
                    v-for="item in activeFilters"
                    :key="item.id"
                    :delete-handler="() => removeFilter(item.type, item.slug)"
                >
                    {{ item.name }}
                </TagPill>

                <a @click="removeAllFilters">({{ trans('page.our.tool.index.remove_all_filters') }})</a>
            </div>

            <h3
                class="text-2xl text-black font-bold"
                v-text="trans('page.tool.index.heading', { count: totalToolCount })"
            />

            <template v-if="toolsAndPossibleDivider.length">
                <template v-for="tool in toolsAndPossibleDivider">
                    <div
                        v-if="tool.divider"
                        :key="`-${tool.id}-`"
                        class="flex items-baseline gap-2 | md:w-2/3 | border-b border-b-gray-300 | pb-3 | font-source-sans"
                    >
                        <h4
                            class="text-xl text-gray-900"
                            v-text="trans('page.our.tool.index.other_tools.title')"
                        />

                        <span
                            class="text-sm text-zinc-400"
                            v-text="`(${trans('page.our.tool.index.other_tools.explanation')})`"
                        />
                    </div>

                    <ToolCard
                        v-else
                        :key="tool.id"
                        :tool="tool"
                        :url="getShowUrl(tool)"
                    />
                </template>
            </template>

            <h4
                v-else-if="toolCountWithoutFilterOrSearch"
                v-text="trans('page.our.tool.index.results_without_filter_or_search')"
            />

            <InertiaPagination :pagination="tools.pagination" />

            <TipCard />
        </div>
    </Layout>
</template>

<script>
import { router } from '@inertiajs/vue2';
import { mapStores } from 'pinia';

import Layout from '@/layouts/SidebarLayout';

import BannerHeader from '@/components/page/BannerHeader';
import ToolCard from '@/components/ToolCard';
import TipCard from '@/pages/tool/components/TipCard';
import InertiaPagination from '@/components/InertiaPagination.vue';
import TagPill from '@/components/TagPill.vue';

import { useToolFilterStore } from '@/stores/tool-filter';

import { getFilterUrlByFilters } from '@/helpers/tool-filter-url';

export default {
    components: {
        TagPill,
        Layout,
        BannerHeader,
        ToolCard,
        TipCard,
        InertiaPagination,
    },
    props: {
        tools: {
            type: Object,
            required: true,
        },
        totalToolCount: {
            type: Number,
            required: true,
        },
        toolCountWithoutFilterOrSearch: {
            type: Number,
            required: true,
        },
        selectedTagFilters: {
            type: [Object, Array],
            default: () => {},
        },
    },
    computed: {
        ...mapStores(useToolFilterStore),
        /**
         * Inserts the divider into the array to be rendered by `v-for` in front of the first unrated element it finds
         *
         * @returns {Array}
         */
        toolsAndPossibleDivider() {
            const indexOfFirstOtherTools = this.tools.data.findIndex(({ institute }) => !institute?.status);

            if (indexOfFirstOtherTools === -1) {
                return this.tools.data;
            }

            const clone = this.tools.data.slice();
            // insert object to signify where divider should be shown
            clone.splice(indexOfFirstOtherTools, 0, {
                id: 'divider',
                divider: true,
            });

            return clone;
        },
        /**
         * Returns a flat array of all the current filters
         *
         * @returns {Array}
         */
        activeFilters() {
            const filters = [];
            Object.values(this.selectedTagFilters).forEach((item) => {
                filters.push(...item);
            });

            return filters;
        },
    },
    /**
     * Runs code after an instance is mounted.
     */
    mounted() {
        this.setFilterData();
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.tool.index.title'),
        };
    },
    methods: {
        /**
         * (re)sets the filter data
         */
        setFilterData() {
            this.setFilterSearchTermFromUrl();
            this.setFilterTagsFromProp();
        },
        /**
         * Sets filter search term in store
         */
        setFilterSearchTermFromUrl() {
            const params = new URLSearchParams(window.location.search);
            const searchTerm = params.has('search') ? params.get('search') : '';

            // eslint-disable-next-line vue/no-undef-properties
            this.toolFilterStore.setSearchTerm(searchTerm);
        },
        /**
         * Sets filter tags in store
         */
        setFilterTagsFromProp() {
            const tagTypesWithSlugs = {};

            if (Object.keys(this.selectedTagFilters).length) {
                Object.keys(this.selectedTagFilters).forEach((tagType) => {
                    tagTypesWithSlugs[tagType] = this.selectedTagFilters[tagType].map((tag) => tag.slug);
                });
            }

            this.toolFilterStore.setTagTypesWithSlugs(tagTypesWithSlugs);
        },
        /**
         * Get the show url for the given tool.
         *
         * @param {object} tool
         * @returns {string}
         */
        getShowUrl(tool) {
            const showRoute = tool.institute ? 'our.tool.show' : 'other.tool.show';

            return route(showRoute, {
                tool: tool.id,
            });
        },
        /**
         * Remove clicked filter from the current filters
         *
         * @param {string} type
         * @param {string} slug
         */
        removeFilter(type, slug) {
            const slugs = this.selectedTagFilters[type].filter((item) => item.slug !== slug).map((tag) => tag.slug);
            this.toolFilterStore.updateTagTypesWithSlugs(type, slugs);

            router.visit(this.getFilterUrl(), {
                preserveScroll: true,
                preserveState: true,
            });
        },
        /**
         * Remove all the filters but keep the search string
         */
        removeAllFilters() {
            this.toolFilterStore.setTagTypesWithSlugs({});

            router.visit(this.getFilterUrl(), {
                preserveScroll: true,
                preserveState: true,
            });
        },
        /**
         * Get the filter url with the good filters
         *
         * @returns {string}
         */
        getFilterUrl() {
            return getFilterUrlByFilters(this.toolFilterStore.searchTerm, this.toolFilterStore.tagTypesWithSlugs);
        },
    },
};
</script>
