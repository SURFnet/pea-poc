<template>
    <span :class="pillClasses" />
</template>

<script>
export default {
    props: {
        variant: {
            type: String,
            default: 'default',

            /**
             * Validates the right variant.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['success', 'warning', 'danger', 'info', 'inactive'].indexOf(value) !== -1;
            },
        },
    },
    computed: {
        /**
         * Determines the styling of the component.
         *
         * @returns {string}
         */
        pillClasses() {
            const genericBase = `
                inline-flex | rounded-full | text-sm font-medium | px-3 py-1
            `.trim();

            const variants = {
                success: 'bg-green-100 text-green-600',
                warning: 'bg-yellow-100 text-yellow-600',
                danger: 'bg-red-100 text-red-600',
                info: 'bg-blue-100 text-blue-00',
                inactive: 'bg-gray-100 text-gray-800',
            };

            const pillClasses = [genericBase];

            pillClasses.push(variants[this.variant]);

            return pillClasses.join(' | ');
        },
    },
};
</script>
