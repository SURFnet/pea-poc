<template>
    <aside class="lg:col-span-2 bg-gray-700 | py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:pt-12 lg:-mb-12">
        <nav class="flex flex-col justify-center items-start | space-y-1">
            <InertiaLink
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
            </InertiaLink>

            <InertiaLink
                :href="route('information-manager.tag.index')"
                class="group | h-16 w-full | flex flex-row items-center | no-underline font-light text-gray-300 border-l-4 hover:no-underline | space-x-2 pl-6 sm:-mr-6"
                :class="menuClasses('information-manager.tag.*')"
            >
                <div class="w-8">
                    <CardViewIcon class="text-gray-300 group-hover:text-white" />
                </div>

                <p class="group-hover:text-white">
                    {{ trans('tag.tag_types.categories') }}
                </p>
            </InertiaLink>

            <InertiaLink
                :href="route('information-manager.custom-field.index')"
                class="group | h-16 w-full | flex flex-row items-center | no-underline font-light text-gray-300 border-l-4 hover:no-underline | space-x-2 pl-6 sm:-mr-6"
                :class="menuClasses('information-manager.custom-field.*')"
            >
                <div class="w-8">
                    <AddIcon class="text-gray-300 group-hover:text-white" />
                </div>

                <p class="group-hover:text-white">
                    {{ trans('custom-field.plural') }}
                </p>
            </InertiaLink>

            <InertiaLink
                :href="route('information-manager.homepage-information.edit')"
                class="group | h-16 w-full | flex flex-row items-center | no-underline font-light text-gray-300 border-l-4 hover:no-underline | space-x-2 pl-6 sm:-mr-6"
                :class="menuClasses('information-manager.institute.*')"
            >
                <div class="w-8">
                    <ELearningMonitorIcon class="text-gray-300 group-hover:text-white" />
                </div>

                <p class="group-hover:text-white">
                    {{ trans('institute.homepage-information.homepage') }}
                </p>
            </InertiaLink>

            <InertiaLink
                :href="route('information-manager.notifications.create')"
                class="group | h-16 w-full | flex flex-row items-center | no-underline font-light text-gray-300 border-l-4 hover:no-underline | space-x-2 pl-6 sm:-mr-6"
                :class="menuClasses('information-manager.notifications.*')"
            >
                <div class="w-8">
                    <MultipleCircleIcon class="text-gray-300 group-hover:text-white" />
                </div>

                <p class="group-hover:text-white">
                    {{ trans('page.information-manager.notifications.title') }}
                </p>
            </InertiaLink>
        </nav>
    </aside>
</template>

<script>
import { isActiveRoute } from '@/helpers/route';

import SearchIcon from '@/components/SearchIcon';
import AddIcon from '@/components/svg/icons/surf/AddIcon';
import CardViewIcon from '@/components/svg/icons/surf/CardViewIcon';
import ELearningMonitorIcon from '@/components/svg/icons/surf/ELearningMonitorIcon';
import MultipleCircleIcon from '@/components/svg/icons/surf/MultipleCircleIcon';

export default {
    components: {
        MultipleCircleIcon,
        SearchIcon,
        AddIcon,
        CardViewIcon,
        ELearningMonitorIcon,
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
