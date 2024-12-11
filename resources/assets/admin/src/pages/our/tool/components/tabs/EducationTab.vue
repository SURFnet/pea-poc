<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.our.tool.show.tabs.education')" />

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

        <div v-if="tool.institute.examples_of_usage">
            <TabSubheading
                :text="trans('institute.tool.attributes.examples_of_usage')"
                :tooltip="tool.institute.tooltips.examples_of_usage"
            />

            <WysiwygOutput :value="tool.institute.examples_of_usage" />
        </div>

        <div v-if="tool.institute.faq">
            <TabSubheading
                :text="trans('institute.tool.attributes.faq')"
                :tooltip="tool.institute.tooltips.faq"
            />

            <WysiwygOutput :value="tool.institute.faq" />
        </div>

        <div v-if="tool.institute.additional_info_heading && tool.institute.additional_info_text">
            <TabSubheading
                :text="tool.institute.additional_info_heading"
                :tooltip="tool.institute.tooltips.additional_info_heading"
            />

            <div class="flex flex-row gap-1">
                <WysiwygOutput :value="tool.institute.additional_info_text" />
            </div>
        </div>

        <div
            v-for="customField in filterCustomFields(tool.institute.custom_fields, 'education')"
            :key="customField.id"
        >
            <TabSubheading :text="customField.title" />

            <WysiwygOutput :value="customField.value" />
        </div>
    </div>
</template>

<script>
import TabHeading from '@/components/TabHeading.vue';
import TabSubheading from '@/components/TabSubheading.vue';
import { filterCustomFields } from '@/helpers/filter-custom-fields';
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
    methods: {
        filterCustomFields,
    },
};
</script>
