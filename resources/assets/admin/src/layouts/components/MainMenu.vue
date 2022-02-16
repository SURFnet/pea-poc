<template>
    <nav class="bg-white border-b border-gray-300">
        <div class="max-w-7xl | mx-auto px-2 sm:px-4 lg:px-8">
            <div class="relative | flex items-center justify-between | h-16">
                <div class="h-full | flex items-center | px-2 lg:px-0">
                    <div class="flex-shrink-0">
                        <InertiaLink :href="route('home.index')" preserve-scroll>
                            <BrandLogo :logo="$page.props.currentUser.institute.logo_full_url" class="h-8 w-auto" />
                        </InertiaLink>
                    </div>
                    <div class="h-full | hidden lg:block | lg:ml-6">
                        <div class="h-full | flex items-center | space-x-2">
                            <InertiaLink
                                v-if="hasPermission('viewAllOurTools')"
                                :href="route('our.tool.index')"
                                class="h-full | flex items-center | font-medium | hover:no-underline | px-3 py-2"
                                :class="menuClasses('our.*')"
                                preserve-scroll
                                v-text="trans('page.menu.our-tools')"
                            />

                            <InertiaLink
                                v-if="hasPermission('viewAllOtherTools')"
                                :href="route('other.tool.index')"
                                class="h-full | flex items-center | font-medium | hover:no-underline | px-3 py-2"
                                :class="menuClasses('other.*')"
                                preserve-scroll
                                v-text="trans('page.menu.other-tools')"
                            />

                            <InertiaLink
                                :href="route('about.index')"
                                class="h-full | flex items-center | font-medium | hover:no-underline | px-3 py-2"
                                :class="menuClasses('about.*')"
                                preserve-scroll
                                v-text="trans('page.menu.about')"
                            />

                            <div v-if="showSeparator()" class="h-1/4 w-px | bg-gray-500" />

                            <InertiaLink
                                v-if="hasPermission('manageOurTools')"
                                :href="route('information-manager.tool.index')"
                                class="h-full | flex items-center | font-medium | hover:no-underline | px-3 py-2"
                                :class="menuClasses('information-manager.*')"
                                preserve-scroll
                                v-text="trans('page.menu.manage-our-tools')"
                            />

                            <InertiaLink
                                v-if="hasPermission('viewAllTools')"
                                :href="route('content-manager.tool.index')"
                                class="h-full | flex items-center | font-medium | hover:no-underline | px-3 py-2"
                                :class="menuClasses('content-manager.*')"
                                preserve-scroll
                                v-text="trans('page.menu.manage-all-tools')"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex lg:hidden">
                    <!-- Mobile menu button -->
                    <button
                        type="button"
                        class="inline-flex items-center justify-center | rounded-md text-sm text-gray-400 hover:text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-400 | p-2"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                        @click="toggleMenu()"
                    >
                        <span class="sr-only" v-text="trans('page.open-menu')" />
                        <svg
                            class="block | h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <svg
                            class="hidden | h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="hidden lg:flex flex-row | lg:ml-4">
                    <div class="flex flex-col items-end justify-center | mr-8">
                        <p class="text-sm font-light text-gray-400" v-text="trans('page.navbar.project')" />
                        <p class="text-gray-400 text-right" v-text="trans('page.navbar.project-name')" />
                    </div>
                    <ChevronDropdown>
                        <template #menu-header>
                            <div class="flex flex-col | text-gray-500">
                                <p class="text-left font-semibold" v-text="$page.props.currentUser.name" />
                                <p class="text-left font-thin" v-text="$page.props.currentUser.institute.short_name" />
                            </div>
                        </template>

                        <DropdownItem
                            v-if="hasPermission('manageTranslations')"
                            :href="route('way2translate.index')"
                            target="_blank"
                            rel="noopener noreferrer"
                            external
                        >
                            {{ trans('page.translation.index.title') }}
                        </DropdownItem>

                        <DropdownItem :href="route('account.logout')" method="post" as="button">
                            {{ trans('action.logout') }}
                        </DropdownItem>
                    </ChevronDropdown>
                </div>
            </div>
        </div>

        <div v-show="openMainMenu" id="mobile-menu" class="lg:hidden">
            <div class="space-y-1 | px-2 pt-2 pb-3">
                <InertiaLink
                    v-if="hasPermission('viewAllOurTools')"
                    :href="route('our.tool.index')"
                    class="block | rounded-md font-medium | hover:no-underline | px-3 py-2"
                    :class="menuClasses('our.*', 'mobile')"
                    preserve-scroll
                    v-text="trans('page.menu.our-tools')"
                />

                <InertiaLink
                    v-if="hasPermission('viewAllOtherTools')"
                    :href="route('other.tool.index')"
                    class="block | rounded-md font-medium hover:no-underline | px-3 py-2"
                    :class="menuClasses('other.*', 'mobile')"
                    preserve-scroll
                    v-text="trans('page.menu.other-tools')"
                />

                <InertiaLink
                    :href="route('about.index')"
                    class="block | rounded-md font-medium hover:no-underline | px-3 py-2"
                    :class="menuClasses('about.*', 'mobile')"
                    preserve-scroll
                    v-text="trans('page.menu.about')"
                />

                <hr v-if="showSeparator()" class="block h-px | bg-gray-500 | p-0 mx-3" />

                <InertiaLink
                    v-if="hasPermission('manageOurTools')"
                    :href="route('information-manager.tool.index')"
                    class="block | rounded-md font-medium hover:no-underline | px-3 py-2"
                    :class="menuClasses('information-manager.*', 'mobile')"
                    preserve-scroll
                    v-text="trans('page.menu.manage-our-tools')"
                />

                <InertiaLink
                    v-if="hasPermission('viewAllTools')"
                    :href="route('content-manager.tool.index')"
                    class="block | rounded-md font-medium | hover:no-underline | px-3 py-2"
                    :class="menuClasses('content-manager.*', 'mobile')"
                    preserve-scroll
                    v-text="trans('page.menu.manage-all-tools')"
                />
            </div>
            <div class="border-t border-gray-500 | pt-4 pb-3">
                <div class="flex items-center px-5">
                    <div class="ml-3">
                        <div class="font-medium text-gray-700">
                            <p>
                                {{ $page.props.currentUser.name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space-y-1 | mt-3 px-2">
                    <a
                        v-if="hasPermission('manageTranslations')"
                        :href="route('way2translate.index')"
                        class="w-full block | rounded-md font-medium text-left text-gray-500 hover:text-blue-500 hover:no-underline | px-3 py-2"
                        target="_blank"
                        rel="noopener noreferrer"
                        external
                    >
                        {{ trans('page.translation.index.title') }}
                    </a>

                    <InertiaLink
                        :href="route('account.logout')"
                        class="w-full block | rounded-md font-medium text-left text-gray-500 hover:text-blue-500 hover:no-underline | px-3 py-2"
                        method="post"
                        as="button"
                    >
                        {{ trans('action.logout') }}
                    </InertiaLink>
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
import { Inertia } from '@inertiajs/inertia';
import { isActiveRoute } from '@/helpers/route';

import BrandLogo from '@/components/BrandLogo';
import ChevronDropdown from '@/components/ChevronDropdown';
import DropdownItem from '@/components/DropdownItem';

export default {
    components: {
        BrandLogo,
        DropdownItem,
        ChevronDropdown,
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            openMainMenu: false,
        };
    },
    /**
     * Runs code after an instance is mounted.
     */
    mounted() {
        this.closeMenuOnSucces();
    },
    methods: {
        isActiveRoute,
        /**
         * Determines if the current user has a given permission.
         *
         * @param {string} permission
         *
         * @returns {string}
         */
        hasPermission(permission) {
            return this.$page.props.currentUser.permissions[permission] === true;
        },
        /**
         * Determines if the separator should be shown between view and manage menu items
         *
         * @returns {boolean}
         */
        showSeparator() {
            return this.hasPermission('manageOurTools') || this.hasPermission('viewAllTools');
        },
        /**
         * Toggle the main menu.
         *
         */
        toggleMenu() {
            this.openMainMenu = !this.openMainMenu;
        },
        /**
         * Close the menu a new page is loaded.
         */
        closeMenuOnSucces() {
            Inertia.on('success', () => {
                this.openMainMenu = false;
            });
        },
        /**
         * Determines the menu classes.
         *
         * @param {string} routeIfActive
         * @param {string} mobile
         *
         * @returns {string}
         */
        menuClasses(routeIfActive, mobile) {
            if (mobile) {
                return this.isActiveRoute(routeIfActive)
                    ? 'text-blue-500'
                    : 'text-gray-500 | border-b-2 border-transparent hover:text-blue-500';
            }

            return this.isActiveRoute(routeIfActive)
                ? 'text-blue-500 border-b-2 border-blue-500'
                : 'text-gray-500 | border-b-2 border-transparent hover:text-blue-500';
        },
    },
};
</script>
