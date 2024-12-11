<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.other.tool.show.tabs.technical')" />

        <div v-if="tool.software_types.length">
            <TabSubheading
                :text="trans('tool.attributes.software_types')"
                :tooltip="trans('institute.tool.tooltip.software_types')"
            />

            <ul class="list-disc">
                <li
                    v-for="softwareType in tool.software_types"
                    :key="softwareType.id"
                    v-text="softwareType.name"
                />
            </ul>
        </div>

        <div v-if="tool.system_requirements">
            <TabSubheading
                :text="trans('tool.attributes.system_requirements')"
                :tooltip="trans('institute.tool.tooltip.system_requirements')"
            />

            <div v-text="tool.system_requirements" />
        </div>

        <div v-if="tool.permissions.see_all_fields && tool.standards.length">
            <TabSubheading
                :text="trans('tool.attributes.standards')"
                :tooltip="trans('institute.tool.tooltip.standards')"
            />

            <ul class="list-disc">
                <li
                    v-for="standard in tool.standards"
                    :key="standard.id"
                    v-text="standard.name"
                />
            </ul>
        </div>

        <div v-if="tool.devices.length">
            <TabSubheading
                :text="trans('tool.attributes.devices')"
                :tooltip="trans('institute.tool.tooltip.devices')"
            />

            <ul class="list-disc">
                <li
                    v-for="device in tool.devices"
                    :key="device.id"
                    v-text="device.name"
                />
            </ul>
        </div>

        <div v-if="tool.operating_systems.length">
            <TabSubheading
                :text="trans('tool.attributes.operating_systems')"
                :tooltip="trans('institute.tool.tooltip.operating_systems')"
            />

            <ul class="list-disc">
                <li
                    v-for="operatingSystem in tool.operating_systems"
                    :key="operatingSystem.id"
                    v-text="operatingSystem.name"
                />
            </ul>
        </div>

        <div v-if="tool.accessibility_facilities">
            <TabSubheading
                :text="trans('tool.attributes.accessibility_facilities')"
                :tooltip="trans('institute.tool.tooltip.accessibility_facilities')"
            />

            <WysiwygOutput :value="tool.accessibility_facilities" />
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
