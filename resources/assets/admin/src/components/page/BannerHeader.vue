<template>
    <header
        class="w-auto flex items-center | border-b | mb-8"
        :class="headerClass"
        :style="headerBackground"
    >
        <div
            class="relative | py-8"
            :class="bannerBoxClass"
        >
            <div class="h-full w-full | flex flex-col justify-center">
                <h2
                    class="text-4xl leading-8 font-semibold | sm:text-3xl sm:leading-8"
                    :class="titleClass"
                    v-text="title"
                />

                <h4
                    v-if="subtitle"
                    class="text-3xl leading-10 font-extralight | sm:text-2xl sm:leading-10 | mb-1"
                    :class="titleClass"
                    v-text="subtitle"
                />

                <form
                    v-if="showSearch"
                    class="w-full"
                    @submit.prevent="search"
                >
                    <div class="w-full | flex flex-row space-x-2">
                        <div class="w-full | flex flex-col items-end">
                            <div class="relative | w-full">
                                <TextInput
                                    v-model="searchTerm"
                                    :placeholder="searchPlaceholder"
                                    class="w-full | mb-2"
                                />

                                <div
                                    v-if="!searchTerm"
                                    class="absolute top-0 bottom-0 right-0 | flex items-center | px-2"
                                >
                                    <FontAwesomeIcon
                                        class="text-gray-300 text-2xl"
                                        icon="search"
                                    />
                                </div>
                            </div>

                            <InertiaLink
                                :href="route('about.index')"
                                class="text-sm text-gray-700 font-semibold underline"
                            >
                                {{ trans('page.home.index.section-header.learn-more') }}
                            </InertiaLink>
                        </div>

                        <Btn
                            type="submit"
                            class="mt-2"
                            style="height: 38px"
                            variant="primary"
                        >
                            {{ trans('page.search') }}
                        </Btn>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-5 flex lg:mt-0 lg:ml-4 | space-x-4">
            <slot />

            <BaseDropdown
                v-if="$slots.more"
                position="right"
            >
                <template #button-content="{ isOpen, toggle }">
                    <button
                        :class="{ 'is-active': isOpen }"
                        @click="toggle"
                    >
                        <FontAwesomeIcon
                            icon="ellipsis-h"
                            size="xs"
                            fixed-width
                        />
                    </button>
                </template>

                <slot name="more" />
            </BaseDropdown>
        </div>
    </header>
</template>

<script>
import { router } from '@inertiajs/vue2';
import { mapStores } from 'pinia';

import BaseDropdown from '@/components/BaseDropdown';
import TextInput from '@/components/form/TextInput';
import Btn from '@/components/Btn';

import { useToolFilterStore } from '@/stores/tool-filter';

import { getFilterUrlByFilters } from '@/helpers/tool-filter-url';

export default {
    components: {
        BaseDropdown,
        TextInput,
        Btn,
    },
    props: {
        title: {
            type: String,
            required: true,
        },
        subtitle: {
            type: String,
            default: null,
        },
        image: {
            type: String,
            default: '',
        },
        page: {
            type: String,
            default: 'home',
            /**
             * Validates the right name.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['home', 'tools', 'about'].indexOf(value) !== -1;
            },
        },
        showSearch: {
            type: Boolean,
            default: true,
        },
        searchPlaceholder: {
            type: String,
            default: null,
        },
    },
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            searchTerm: '',
        };
    },
    computed: {
        ...mapStores(useToolFilterStore),
        /**
         * Assigns the background image
         *
         * @returns {string}
         */
        headerBackground() {
            if (this.image) {
                return `background-image: url(${this.image}); background-size: cover; background-position: center`;
            }

            return 'background-color: #fff';
        },
        /**
         * Adjusts the header class depending on the page prop
         *
         * @returns {string}
         */
        headerClass() {
            switch (this.page) {
                case 'tools':
                    return 'h-56 | justify-start | lg:-ml-5 lg:-mr-8';
                default:
                    return 'h-72 | justify-center';
            }
        },
        /**
         * Adjusts the header class depending on the page prop
         *
         * @returns {string}
         */
        bannerBoxClass() {
            switch (this.page) {
                case 'home':
                    return 'w-2/3 | px-8 | rounded-sm white-transparent';
                case 'tools':
                    return 'w-2/3 | px-8 | rounded-sm ml-4 white-transparent';
                case 'about':
                    return 'w-11/12';
                default:
                    return '';
            }
        },
        /**
         * Adjusts the title class depending on the page prop
         *
         * @returns {string}
         */
        titleClass() {
            switch (this.page) {
                case 'about':
                    return 'text-white';
                default:
                    return 'text-black';
            }
        },
    },
    watch: {
        /**
         * Watch for changes in store
         *
         * @param {string} newValue
         */
        // eslint-disable-next-line func-names, vue/no-undef-properties
        'toolFilterStore.searchTerm': function (newValue) {
            this.searchTerm = newValue;
        },
    },
    /**
     * Runs code after an instance is mounted.
     */
    mounted() {
        this.searchTerm = this.toolFilterStore.searchTerm;
    },
    methods: {
        /**
         * Handles the search request.
         */
        search() {
            this.toolFilterStore.setSearchTerm(this.searchTerm);

            const filterUrl = getFilterUrlByFilters(
                this.toolFilterStore.searchTerm,
                this.toolFilterStore.tagTypesWithSlugs
            );

            router.visit(filterUrl, {
                preserveScroll: true,
                preserveState: true,
            });
        },
    },
};
</script>

<style scoped>
.white-transparent {
    background-color: rgba(255, 255, 255, 0.8);
}
</style>
