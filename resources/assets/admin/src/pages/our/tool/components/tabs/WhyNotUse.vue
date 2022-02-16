<template>
    <div class="space-y-8">
        <div class="space-y-4">
            <h3 class="text-2xl text-black font-bold" v-text="trans('page.our.tool.show.headings.why_not_use')" />

            <!-- eslint-disable-next-line vue/no-v-html -->
            <p v-html="tool.why_unfit_display" />
        </div>

        <div class="space-y-4">
            <h3 class="text-2xl text-black font-bold" v-text="trans('page.our.tool.show.headings.alternative_tool')" />

            <ToolCard
                v-if="tool.alternative_tool"
                :tool="tool.alternative_tool"
                :url="route('our.tool.show', tool.alternative_tool)"
            />

            <p v-else v-text="trans('page.our.tool.show.why_not_use.no_alternative')" />
        </div>
    </div>
</template>

<script>
import ToolCard from '@/components/ToolCard';

export default {
    components: {
        ToolCard,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
};
</script>
