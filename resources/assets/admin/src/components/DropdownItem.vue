<template>
    <component
        v-bind="$attrs"
        :is="componentType"
        :class="[classList, 'js-dropdown-item']"
        preserve-scroll
        :rel="rel"
        :target="target"
    >
        <slot />
    </component>
</template>
<script>
export default {
    inheritAttrs: false,

    props: {
        external: {
            type: Boolean,
            default: false,
        },
        current: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Determines the component type.
         *
         * @returns {string}
         */
        componentType() {
            if (this.external === true) {
                return 'a';
            }

            return 'InertiaLink';
        },
        /**
         * Determines the  CSS classes based on the state of the component
         *
         * @returns {string}
         *
         */
        classList() {
            const classList = [
                'w-full block | text-left text-sm font-normal hover:no-underline hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary | px-4 py-2',
            ];

            if (this.current) {
                classList.push('text-gray-700 font-bold border-l-4 border-blue-600 | pl-3');
            } else if (this.disabled) {
                classList.push('text-gray-300 cursor-not-allowed');
            } else {
                classList.push('text-gray-700 cursor-pointer');
            }

            return classList.join(' | ');
        },
        /**
         * Determines the rel
         *
         * @returns {string|null}
         */
        rel() {
            return this.external ? 'noopener noreferrer' : null;
        },
        /**
         * Determines the target
         *
         * @returns {string|null}
         */
        target() {
            return this.external ? '_bank' : '';
        },
    },
};
</script>
