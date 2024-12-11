<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.other.tool.show.tabs.privacy_and_security')" />

        <div v-if="tool.permissions.see_all_fields && tool.privacy_analysis">
            <TabSubheading
                :text="trans('tool.attributes.privacy_analysis')"
                :tooltip="trans('institute.tool.tooltip.privacy_analysis')"
            />

            <WysiwygOutput :value="tool.privacy_analysis" />
        </div>

        <div v-if="tool.permissions.see_all_fields && tool.privacy_policy_url">
            <TabSubheading
                :text="trans('tool.attributes.privacy_policy_url')"
                :tooltip="trans('institute.tool.tooltip.privacy_policy_url')"
            />

            <a
                :href="tool.privacy_policy_url"
                target="_blank"
                rel="noreferrer noopener"
                v-text="trans('tool.attributes.privacy_policy_url')"
            />
        </div>

        <div v-if="tool.permissions.see_all_fields && tool.model_processor_agreement_url">
            <TabSubheading
                :text="trans('tool.attributes.model_processor_agreement_url')"
                :tooltip="trans('institute.tool.tooltip.model_processor_agreement_url')"
            />

            <a
                :href="tool.model_processor_agreement_url"
                target="_blank"
                rel="noreferrer noopener"
                v-text="trans('tool.attributes.model_processor_agreement_url')"
            />
        </div>

        <div v-if="tool.personal_data">
            <TabSubheading
                :text="trans('tool.attributes.personal_data')"
                :tooltip="trans('institute.tool.tooltip.personal_data')"
            />

            <WysiwygOutput :value="tool.personal_data" />
        </div>

        <div v-if="tool.supplier_country_display">
            <TabSubheading
                :text="trans('tool.attributes.supplier_country')"
                :tooltip="trans('institute.tool.tooltip.supplier_country')"
            />

            <div v-text="tool.supplier_country_display" />
        </div>

        <div v-if="tool.certifications.length">
            <TabSubheading
                :text="trans('tool.attributes.certifications')"
                :tooltip="trans('institute.tool.tooltip.certifications')"
            />

            <ul class="list-disc">
                <li
                    v-for="certification in tool.certifications"
                    :key="certification.id"
                    v-text="certification.name"
                />
            </ul>
        </div>

        <div v-if="tool.permissions.see_all_fields && tool.dtia_by_external_url">
            <TabSubheading
                :text="trans('tool.attributes.dtia_by_external_url')"
                :tooltip="trans('institute.tool.tooltip.dtia_by_external_url')"
            />

            <a
                :href="tool.dtia_by_external_url"
                target="_blank"
                rel="noreferrer noopener"
                v-text="trans('tool.attributes.dtia_by_external_url')"
            />
        </div>

        <div v-if="tool.permissions.see_all_fields && tool.dpia_by_external_url">
            <TabSubheading
                :text="trans('tool.attributes.dpia_by_external_url')"
                :tooltip="trans('institute.tool.tooltip.dpia_by_external_url')"
            />

            <a
                :href="tool.dpia_by_external_url"
                target="_blank"
                rel="noreferrer noopener"
                v-text="trans('tool.attributes.dpia_by_external_url')"
            />
        </div>

        <div v-if="tool.permissions.see_all_fields && tool.jurisdiction">
            <TabSubheading
                :text="trans('tool.attributes.jurisdiction')"
                :tooltip="trans('institute.tool.tooltip.jurisdiction')"
            />

            <div v-text="tool.jurisdiction" />
        </div>

        <div v-if="tool.data_processing_locations.length">
            <TabSubheading
                :text="trans('tool.attributes.data_processing_locations')"
                :tooltip="trans('institute.tool.tooltip.data_processing_locations')"
            />

            <ul class="list-disc">
                <li
                    v-for="dataProcessingLocation in tool.data_processing_locations"
                    :key="dataProcessingLocation.id"
                    v-text="dataProcessingLocation.name"
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
