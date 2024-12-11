<template>
    <div>
        <h2
            class="mb-4 | text-2xl leading-6 text-slate-600 italic"
            v-text="trans('page.our.tool.show.content_manager_filled_out')"
        />

        <FormGroup :title="trans('page.content-manager.tool.form.headings.product')">
            <FormColumn first>
                <InfoLabelAndText
                    :label="trans('tool.attributes.name')"
                    :text="tool.name"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.supplier')"
                    :text="tool.supplier"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.supplier_url')"
                    :text="tool.supplier_url"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.description_short_en')"
                    :html="tool.description_short_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.description_short_nl')"
                    :html="tool.description_short_nl"
                />
            </FormColumn>

            <FormColumn>
                <InfoTagGroup
                    :tags="tool.features"
                    :label="trans('tool.attributes.features')"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.description_long_en')"
                    :html="tool.description_long_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.description_long_nl')"
                    :html="tool.description_long_nl"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.addons_en')"
                    :html="tool.addons_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.addons_nl')"
                    :html="tool.addons_nl"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.technical')">
            <FormColumn first>
                <InfoTagGroup
                    :tags="tool.software_types"
                    :label="trans('tool.attributes.software_types')"
                />

                <InfoTagGroup
                    :tags="tool.devices"
                    :label="trans('tool.attributes.devices')"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.system_requirements_en')"
                    :text="tool.system_requirements_en"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.system_requirements_nl')"
                    :text="tool.system_requirements_nl"
                />
            </FormColumn>

            <FormColumn>
                <InfoTagGroup
                    :tags="tool.standards"
                    :label="trans('tool.attributes.standards')"
                />

                <InfoTagGroup
                    :tags="tool.operating_systems"
                    :label="trans('tool.attributes.operating_systems')"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.privacy_and_security')">
            <FormColumn first>
                <InfoLabelAndText
                    :label="trans('tool.attributes.supplier_country_display')"
                    :text="tool.supplier_country"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.personal_data_en')"
                    :html="tool.personal_data_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.personal_data_nl')"
                    :html="tool.personal_data_nl"
                />

                <InfoTagGroup
                    :tags="tool.data_processing_locations"
                    :label="trans('tool.attributes.data_processing_locations')"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.privacy_policy_url')"
                    :text="tool.privacy_policy_url"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.model_processor_agreement_url')"
                    :text="tool.model_processor_agreement_url"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.privacy_analysis')"
                    :html="tool.privacy_analysis"
                />
            </FormColumn>

            <FormColumn>
                <InfoLabelAndBoolean
                    :label="trans('tool.attributes.supplier_agrees_with_surf_standards')"
                    :number-as-boolean="tool.supplier_agrees_with_surf_standards"
                />

                <InfoTagGroup
                    :tags="tool.certifications"
                    :label="trans('tool.attributes.certifications')"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.dtia_by_external_url')"
                    :text="tool.dtia_by_external_url"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.dpia_by_external_url')"
                    :text="tool.dpia_by_external_url"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.jurisdiction')"
                    :text="tool.jurisdiction"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.support')">
            <FormColumn first>
                <InfoLabelAndText
                    :label="trans('tool.attributes.instructions_manual_1_url_en')"
                    :text="tool.instructions_manual_1_url_en"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.instructions_manual_1_url_nl')"
                    :text="tool.instructions_manual_1_url_nl"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.instructions_manual_2_url_en')"
                    :text="tool.instructions_manual_2_url_en"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.instructions_manual_2_url_nl')"
                    :text="tool.instructions_manual_2_url_nl"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.instructions_manual_3_url_en')"
                    :text="tool.instructions_manual_3_url_en"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.instructions_manual_3_url_nl')"
                    :text="tool.instructions_manual_3_url_nl"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.how_does_it_work_en')"
                    :text="tool.how_does_it_work_en"
                />

                <InfoLabelAndText
                    :label="trans('tool.attributes.how_does_it_work_nl')"
                    :text="tool.how_does_it_work_nl"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.support_for_teachers_en')"
                    :html="tool.support_for_teachers_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.support_for_teachers_nl')"
                    :html="tool.support_for_teachers_nl"
                />
            </FormColumn>

            <FormColumn>
                <InfoLabelAndHtml
                    :label="trans('tool.attributes.availability_surf')"
                    :html="tool.availability_surf"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.accessibility_facilities_en')"
                    :html="tool.accessibility_facilities_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.accessibility_facilities_nl')"
                    :html="tool.accessibility_facilities_nl"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.education')">
            <FormColumn first>
                <InfoLabelAndHtml
                    :label="trans('tool.attributes.use_for_education_en')"
                    :html="tool.use_for_education_en"
                />

                <InfoLabelAndHtml
                    :label="trans('tool.attributes.use_for_education_nl')"
                    :html="tool.use_for_education_nl"
                />

                <InfoTagGroup
                    :tags="tool.working_methods"
                    :label="trans('tool.attributes.working_methods')"
                />
            </FormColumn>

            <FormColumn>
                <InfoTagGroup
                    :tags="tool.target_groups"
                    :label="trans('tool.attributes.target_groups')"
                />

                <InfoTagGroup
                    :tags="tool.complexity"
                    :label="trans('tool.attributes.complexity')"
                />
            </FormColumn>
        </FormGroup>
    </div>
</template>

<script>
import FormGroup from '@/components/FormGroup.vue';
import FormColumn from '@/components/FormColumn.vue';
import InfoLabelAndText from '@/pages/information-manager/tools/components/InfoLabelAndText.vue';
import InfoLabelAndHtml from '@/pages/information-manager/tools/components/InfoLabelAndHtml.vue';
import InfoTagGroup from '@/pages/information-manager/tools/components/InfoTagGroup.vue';
import InfoLabelAndBoolean from '@/pages/information-manager/tools/components/InfoLabelAndBoolean.vue';

export default {
    components: {
        InfoLabelAndBoolean,
        InfoTagGroup,
        FormGroup,
        FormColumn,
        InfoLabelAndText,
        InfoLabelAndHtml,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
};
</script>
