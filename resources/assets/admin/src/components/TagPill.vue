<template>
    <div class="inline-flex items-center | rounded-full overflow-hidden | border border-gray-300">
        <component
            :is="href ? 'InertiaLink' : 'div'"
            :href="href"
            class="px-3 py-1"
        >
            <div class="flex flex-row text-sm font-source-sans font-medium text-gray-900">
                <span>
                    <slot />
                </span>

                <template v-if="canDelete">
                    <svg
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        class="h-4 w-4 self-center cursor-pointer"
                        @click="deleteHandler"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </template>
            </div>
        </component>
    </div>
</template>

<script>
export default {
    props: {
        href: {
            type: String,
            default: null,
        },
        deleteHandler: {
            type: Function,
            default: null,
        },
    },
    computed: {
        /**
         * Determine of there is a deleteHandler
         *
         * @returns {boolean}
         */
        canDelete() {
            return this.deleteHandler !== null;
        },
    },
};
</script>
