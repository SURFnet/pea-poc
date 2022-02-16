<template>
    <nav class="flex space-x-2 | pt-6" :class="alignmentClass">
        <slot />
    </nav>
</template>

<script>
export default {
    props: {
        align: {
            type: String,
            default: 'between',
            /**
             * Validates the right type.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['between', 'end'].indexOf(value) !== -1;
            },
        },
    },
    computed: {
        /**
         * Determines the alignment class of the footer.
         *
         * @returns {string}
         */
        alignmentClass() {
            if (this.align === 'between') {
                return 'justify-between';
            }

            return 'justify-end';
        },
    },
};
</script>
