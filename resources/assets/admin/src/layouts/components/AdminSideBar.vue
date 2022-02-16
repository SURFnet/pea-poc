<template>
    <aside class="lg:col-span-2 bg-gray-700 | py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:pt-12 lg:-mb-12">
        <nav class="flex flex-col justify-center items-start | space-y-1">
            <inertia-link
                :href="route('information-manager.tool.index')"
                class="group | h-16 w-full | flex flex-row items-center | no-underline font-light text-gray-300 border-l-4 hover:no-underline | space-x-2 pl-6 sm:-mr-6"
                :class="menuClasses('information-manager.tool.*')"
            >
                <div class="w-8">
                    <SearchIcon class="text-gray-300 group-hover:text-white" />
                </div>
                <p class="group-hover:text-white">
                    {{ trans('tool.plural') }}
                </p>
            </inertia-link>

            <inertia-link
                :href="route('information-manager.category.index')"
                class="group | h-16 w-full | flex flex-row items-center | no-underline font-light text-gray-300 border-l-4 hover:no-underline | space-x-2 pl-6 sm:-mr-6"
                :class="menuClasses('information-manager.category.*')"
            >
                <div class="w-8">
                    <ListIcon class="text-gray-300 group-hover:text-white" />
                </div>
                <p class="group-hover:text-white">
                    {{ trans('category.plural') }}
                </p>
            </inertia-link>
        </nav>
    </aside>
</template>

<script>
import { isActiveRoute } from '@/helpers/route';

import SearchIcon from '@/components/SearchIcon';
import ListIcon from '@/components/ListIcon';

export default {
    components: {
        SearchIcon,
        ListIcon,
    },
    methods: {
        isActiveRoute,
        /**
         * Determines the menu classes.
         *
         * @param {string} routeIfActive
         *
         * @returns {string}
         */
        menuClasses(routeIfActive) {
            return this.isActiveRoute(routeIfActive)
                ? 'bg-gray-600 border-blue-200 text-blue-200'
                : 'border-gray-700 hover:bg-gray-600 hover:border-blue-200';
        },
    },
};
</script>
