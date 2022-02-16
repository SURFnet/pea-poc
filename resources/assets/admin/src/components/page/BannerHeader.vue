<template>
    <header class="w-auto flex items-center | border-b | mb-8" :class="headerClass" :style="headerBackground">
        <div class="relative | py-8" :class="bannerBoxClass">
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
                <form v-if="showSearch" class="w-full" @submit.prevent="search">
                    <div class="w-full | flex flex-row space-x-2">
                        <div class="relative | w-full | flex flex-col items-end">
                            <TextInput v-model="searchTerm" :placeholder="searchPlaceholder" class="w-full | mb-2" />

                            <font-awesome-icon
                                v-if="!searchTerm"
                                class="absolute | text-gray-300 text-2xl top-3 right-2"
                                icon="search"
                            />

                            <inertia-link
                                :href="route('about.index')"
                                class="text-sm text-gray-700 font-semibold underline"
                                v-text="trans('page.home.index.section-header.learn-more')"
                            />
                        </div>

                        <Btn
                            type="submit"
                            class="mt-2"
                            style="height: 38px"
                            variant="primary"
                            v-text="trans('page.search')"
                        />
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-5 flex lg:mt-0 lg:ml-4 | space-x-4">
            <slot />

            <BaseDropdown v-if="$slots.more" position="right">
                <template #button-content="{ isOpen, toggle }">
                    <button :class="{ 'is-active': isOpen }" @click="toggle">
                        <font-awesome-icon icon="ellipsis-h" size="xs" fixed-width />
                    </button>
                </template>

                <slot name="more" />
            </BaseDropdown>
        </div>
    </header>
</template>

<script>
import BaseDropdown from '@/components/BaseDropdown';
import TextInput from '@/components/form/TextInput';

import Btn from '@/components/Btn';

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
                return ['home', 'our-tools', 'other-tools', 'about'].indexOf(value) !== -1;
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
     * holds the dats
     *
     * @returns {object}
     */
    data() {
        return {
            searchTerm: '',
        };
    },
    computed: {
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
                case 'other-tools':
                    return 'h-64 | justify-start | lg:-ml-5';
                case 'our-tools':
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
                case 'other-tools':
                    return 'w-2/3 | px-8 | rounded-sm bottom-heavy-border ml-5 white-transparent';
                case 'our-tools':
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
        /**
         * Adjusts the subtitle class depending on the page prop
         *
         * @returns {string}
         */
        subtitleClass() {
            switch (this.page) {
                case 'about':
                    return 'text-white';
                default:
                    return 'text-black';
            }
        },
    },
    /**
     * Preset the search term from query string
     */
    mounted() {
        const params = new URLSearchParams(window.location.search);

        if (params.has('search')) {
            this.searchTerm = params.get('search');
        }
    },
    methods: {
        /**
         * Handles the search request.
         */
        search() {
            let targetDomain = 'our';

            if (route().current('other.*')) {
                targetDomain = 'other';
            }

            const params = new URLSearchParams(window.location.search);

            params.set('search', this.searchTerm);

            this.$inertia.visit(`${route(`${targetDomain}.tool.index`)}?${params.toString()}`, {
                preserveScroll: route().current(`${targetDomain}.*`),
                preserveState: route().current(`${targetDomain}.*`),
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
