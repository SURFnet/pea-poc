<template>
    <div :class="columnClass">
        <slot />
    </div>
</template>

<script>
export default {
    props: {
        first: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Defines the column class depending on the 'first' prop
         *
         * @returns {string}
         */
        columnClass() {
            if (this.first) {
                return 'md:col-span-1 | space-y-4';
            }

            return 'md:col-span-1 | space-y-4 | mt-6 md:mt-0';
        },
    },
};
</script>
