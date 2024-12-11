<template>
    <h4 class="text-surf-h4 leading-surf-h4 text-surf-black font-surf-h4">
        {{ text }}

        <ToolTip
            v-if="tooltip"
            class="font-medium"
            :text="tooltip"
        />
    </h4>
</template>

<script>
import ToolTip from '@/components/ToolTip.vue';

export default {
    components: { ToolTip },
    props: {
        text: {
            type: String,
            required: true,
        },
        tooltip: {
            type: String,
            default: null,
        },
    },
};
</script>
