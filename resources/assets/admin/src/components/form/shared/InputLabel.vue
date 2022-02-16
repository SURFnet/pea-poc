<template>
    <label class="block | font-source-sans text-gray-900" :class="labelClass">
        <slot />

        <IsRequired v-if="required" />
    </label>
</template>

<script>
import IsRequired from '@/components/form/shared/IsRequired';

export default {
    components: { IsRequired },
    props: {
        required: {
            type: Boolean,
            default: false,
        },
        largeLabel: {
            type: Boolean,
            default: false,
        },
        normalWeight: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Label font weight decided though largeLabel prop
         *
         *@returns {string}
         */
        labelClass() {
            const attributes = [];

            if (!this.largeLabel) {
                attributes.push('text-sm');
            }
            if (this.normalWeight) {
                attributes.push('font-normal');
            } else {
                attributes.push('font-medium');
            }

            return attributes.join(' ');
        },
    },
};
</script>
