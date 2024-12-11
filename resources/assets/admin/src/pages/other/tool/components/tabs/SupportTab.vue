<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.other.tool.show.tabs.support')" />

        <div v-if="tool.instructions_manual_1_url">
            <TabSubheading :text="trans('tool.attributes.instructions_manual_1_url')" />

            <Url :link="tool.instructions_manual_1_url" />
        </div>

        <div v-if="tool.instructions_manual_2_url">
            <TabSubheading :text="trans('tool.attributes.instructions_manual_2_url')" />

            <Url :link="tool.instructions_manual_2_url" />
        </div>

        <div v-if="tool.instructions_manual_3_url">
            <TabSubheading :text="trans('tool.attributes.instructions_manual_3_url')" />

            <Url :link="tool.instructions_manual_3_url" />
        </div>

        <div v-if="tool.how_does_it_work">
            <TabSubheading :text="trans('tool.attributes.how_does_it_work')" />

            <div v-text="tool.how_does_it_work" />
        </div>

        <div v-if="tool.support_for_teachers">
            <TabSubheading
                :text="trans('tool.attributes.support_for_teachers')"
                :tooltip="trans('institute.tool.tooltip.support_for_teachers')"
            />

            <WysiwygOutput :value="tool.support_for_teachers" />
        </div>
    </div>
</template>

<script>
import TabHeading from '@/components/TabHeading.vue';
import TabSubheading from '@/components/TabSubheading.vue';
import WysiwygOutput from '@/components/WysiwygOutput';
import Url from '@/components/Url.vue';

export default {
    components: {
        Url,
        TabHeading,
        TabSubheading,
        WysiwygOutput,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
};
</script>
