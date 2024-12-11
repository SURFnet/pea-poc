<template>
    <BaseDropdown
        position="right"
        push-left
    >
        <template #button="{ isOpen, toggle }">
            <button
                class="button button-rounded border-none focus:outline-none | p-2"
                :class="{ 'is-active': isOpen }"
                @click="toggle"
            >
                <component
                    :is="componentName(locale)"
                    class="w-5 h-5 | rounded-full"
                />
            </button>
        </template>

        <a
            v-for="activeLocale in activeLocales"
            :key="activeLocale.native + activeLocale.code"
            :href="activeLocale.url"
            class="dropdown-item hover:no-underline | flex items-center"
        >
            <component
                :is="componentName(activeLocale.code)"
                class="w-5 h-5 | rounded-full | mr-2"
            />
            {{ activeLocale.native }}
        </a>
    </BaseDropdown>
</template>

<script>
import BaseDropdown from '@/components/BaseDropdown';

import FlagEN from '@/components/svg/FlagEN';
import FlagNL from '@/components/svg/FlagNL';

export default {
    components: {
        BaseDropdown,
        FlagEN,
        FlagNL,
    },
    props: {
        activeLocales: {
            type: Array,
            required: true,
        },
        locale: {
            type: String,
            required: true,
        },
    },
    methods: {
        /**
         * Generate the component name for the flag SVG.
         *
         * @param {string} localeCode
         *
         * @returns {string}
         */
        componentName(localeCode) {
            return `Flag${localeCode.toUpperCase()}`;
        },
    },
};
</script>
