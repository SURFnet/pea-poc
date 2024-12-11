<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.our.tool.show.tabs.support')" />

        <div v-if="tool.institute.availability">
            <TabSubheading
                :text="trans('institute.tool.attributes.availability')"
                :tooltip="tool.institute.tooltips.availability"
            />

            <div v-text="tool.institute.availability" />
        </div>

        <div v-if="tool.institute.licensing">
            <TabSubheading
                :text="trans('institute.tool.attributes.licensing')"
                :tooltip="tool.institute.tooltips.licensing"
            />

            <div v-text="tool.institute.licensing" />
        </div>

        <div v-if="tool.institute.request_access">
            <TabSubheading
                :text="trans('institute.tool.attributes.request_access')"
                :tooltip="tool.institute.tooltips.request_access"
            />

            <WysiwygOutput :value="tool.institute.request_access" />
        </div>

        <div v-if="tool.institute.how_to_login">
            <TabSubheading
                :text="trans('institute.tool.attributes.how_to_login')"
                :tooltip="tool.institute.tooltips.how_to_login"
            />

            <div v-text="tool.institute.how_to_login" />
        </div>

        <div v-if="tool.institute.instructions_manual_1_url">
            <TabSubheading
                :text="trans('institute.tool.attributes.instructions_manual_1_url')"
                :tooltip="tool.institute.tooltips.instructions_manual_1_url"
            />

            <Url :link="tool.institute.instructions_manual_1_url" />
        </div>

        <div v-if="tool.institute.instructions_manual_2_url">
            <TabSubheading
                :text="trans('institute.tool.attributes.instructions_manual_2_url')"
                :tooltip="tool.institute.tooltips.instructions_manual_2_url"
            />

            <Url :link="tool.institute.instructions_manual_2_url" />
        </div>

        <div v-if="tool.institute.instructions_manual_3_url">
            <TabSubheading
                :text="trans('institute.tool.attributes.instructions_manual_3_url')"
                :tooltip="tool.institute.tooltips.instructions_manual_3_url"
            />

            <Url :link="tool.institute.instructions_manual_3_url" />
        </div>

        <div v-if="tool.institute.instructions">
            <TabSubheading
                :text="trans('institute.tool.attributes.instructions')"
                :tooltip="tool.institute.tooltips.instructions"
            />

            <WysiwygOutput :value="tool.institute.instructions" />
        </div>

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

        <div
            v-for="customField in filterCustomFields(tool.institute.custom_fields, 'support')"
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
    methods: {
        filterCustomFields,
    },
};
</script>
