<template>
    <label
        class="block | font-source-sans text-gray-900"
        :class="labelClass"
    >
        <slot />

        <ToolTip
            v-if="toolTip"
            :text="toolTip"
        />

        <IsRequired v-if="required" />
    </label>
</template>

<script>
import IsRequired from '@/components/form/shared/IsRequired';
import ToolTip from '@/components/ToolTip.vue';

export default {
    components: { ToolTip, IsRequired },
    props: {
        required: {
            type: Boolean,
            default: false,
        },
        toolTip: {
            type: String,
            default: null,
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
