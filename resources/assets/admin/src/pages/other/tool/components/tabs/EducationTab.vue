<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.other.tool.show.tabs.education')" />

        <div v-if="tool.use_for_education">
            <TabSubheading :text="trans('tool.attributes.use_for_education')" />

            <WysiwygOutput :value="tool.use_for_education" />
        </div>

        <div v-if="tool.working_methods.length">
            <TabSubheading :text="trans('tool.attributes.working_methods')" />

            <ul class="list-disc">
                <li
                    v-for="workingMethod in tool.working_methods"
                    :key="workingMethod.id"
                    v-text="workingMethod.name"
                />
            </ul>
        </div>
    </div>
</template>

<script>
import TabHeading from '@/components/TabHeading.vue';
import TabSubheading from '@/components/TabSubheading.vue';
import WysiwygOutput from '@/components/WysiwygOutput';

export default {
    components: {
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
