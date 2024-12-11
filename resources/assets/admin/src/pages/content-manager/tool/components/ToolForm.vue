<template>
    <div class="md:px-8 md:pt-4">
        <FormGroup :title="trans('page.content-manager.tool.form.headings.product')">
            <FormColumn first>
                <!-- eslint-disable vue/no-undef-properties -->
                <TextInput
                    ref="name"
                    v-model="internalForm.name"
                    name="name"
                    :label="trans('tool.attributes.name')"
                    :error="form.errors.name"
                    required
                    large-label
                    normal-weight
                />
                <!-- eslint-enable vue/no-undef-properties -->

                <TextInput
                    ref="supplier"
                    v-model="internalForm.supplier"
                    name="supplier"
                    :label="trans('tool.attributes.supplier')"
                    :error="form.errors.supplier"
                    large-label
                    normal-weight
                />

                <TextInput
                    ref="supplier_url"
                    v-model="internalForm.supplier_url"
                    name="supplier_url"
                    :label="trans('tool.attributes.supplier_url')"
                    :error="form.errors.supplier_url"
                    large-label
                />

                <RichText
                    ref="description_short_en"
                    v-model="internalForm.description_short_en"
                    :label="trans('tool.attributes.description_short_en')"
                    :error="form.errors.description_short_en"
                    required
                    :text="trans('page.content-manager.tool.form.captions.short-description')"
                />

                <RichText
                    ref="description_short_nl"
                    v-model="internalForm.description_short_nl"
                    :label="trans('tool.attributes.description_short_nl')"
                    :error="form.errors.description_short_nl"
                    required
                    :text="trans('page.content-manager.tool.form.captions.short-description')"
                />

                <TagInput
                    ref="features"
                    v-model="internalForm.features"
                    :label="trans('tool.attributes.features')"
                    :error="form.errors.features"
                    :available-tags="features"
                    large-label
                />

                <RichText
                    ref="description_long_en"
                    v-model="internalForm.description_long_en"
                    :label="trans('tool.attributes.description_long_en')"
                    :error="form.errors.description_long_en"
                />

                <RichText
                    ref="description_long_nl"
                    v-model="internalForm.description_long_nl"
                    :label="trans('tool.attributes.description_long_nl')"
                    :error="form.errors.description_long_nl"
                />

                <RichText
                    ref="addons_en"
                    v-model="internalForm.addons_en"
                    :label="trans('tool.attributes.addons_en')"
                    :error="form.errors.addons_en"
                />

                <RichText
                    ref="addons_nl"
                    v-model="internalForm.addons_nl"
                    :label="trans('tool.attributes.addons_nl')"
                    :error="form.errors.addons_nl"
                />

                <div v-if="updatedAt">
                    <InputDisplay
                        :label="trans('tool.attributes.updated_at')"
                        :value="dateTime(updatedAt)"
                    />
                </div>
            </FormColumn>

            <FormColumn class="md:col-span-1 | mt-6 md:mt-0">
                <!-- eslint-disable vue/no-undef-properties -->
                <FileInput
                    ref="logo_filename"
                    name="file"
                    :label="trans('tool.attributes.logo_filename')"
                    :error="internalForm.errors.logo_filename"
                    :image-preview="internalForm.logo_url"
                    large-label
                    :required="!tool"
                    :text="trans('page.content-manager.tool.form.captions.image-format-sm')"
                    @change="(file) => (internalForm.logo_filename = file)"
                />
                <!-- eslint-enable vue/no-undef-properties -->

                <FileInput
                    ref="image_1_filename"
                    name="file"
                    :label="trans('tool.attributes.image_1_filename')"
                    :error="internalForm.errors.image_1_filename"
                    :image-preview="internalForm.image_1_url"
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.image-format-lg')"
                    @change="(file) => (internalForm.image_1_filename = file)"
                />

                <FileInput
                    ref="image_2_filename"
                    name="file"
                    :label="trans('tool.attributes.image_2_filename')"
                    :error="internalForm.errors.image_2_filename"
                    :image-preview="internalForm.image_2_url"
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.image-format-lg')"
                    @change="(file) => (internalForm.image_2_filename = file)"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup
            :title="trans('page.content-manager.tool.form.headings.technical')"
            gray-background
        >
            <FormColumn first>
                <TagInput
                    ref="software_types"
                    v-model="internalForm.software_types"
                    :label="trans('tool.attributes.software_types')"
                    :error="form.errors.software_types"
                    :available-tags="softwareTypes"
                    large-label
                />

                <TagInput
                    ref="devices"
                    v-model="internalForm.devices"
                    :label="trans('tool.attributes.devices')"
                    :error="form.errors.devices"
                    :available-tags="devices"
                    large-label
                />

                <TextInput
                    ref="system_requirements_en"
                    v-model="internalForm.system_requirements_en"
                    name="system_requirements_en"
                    :label="trans('tool.attributes.system_requirements_en')"
                    :error="form.errors.system_requirements_en"
                    large-label
                />

                <TextInput
                    ref="system_requirements_nl"
                    v-model="internalForm.system_requirements_nl"
                    name="system_requirements_nl"
                    :label="trans('tool.attributes.system_requirements_nl')"
                    :error="form.errors.system_requirements_nl"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TagInput
                    ref="standards"
                    v-model="internalForm.standards"
                    :label="trans('tool.attributes.standards')"
                    :error="form.errors.standards"
                    :available-tags="standards"
                    large-label
                />

                <TagInput
                    ref="operating_systems"
                    v-model="internalForm.operating_systems"
                    :label="trans('tool.attributes.operating_systems')"
                    :error="form.errors.operating_systems"
                    :available-tags="operatingSystems"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.privacy_and_security')">
            <FormColumn first>
                <SelectInput
                    ref="supplier_country"
                    v-model="internalForm.supplier_country"
                    name="supplier_country"
                    :label="trans('tool.attributes.supplier_country')"
                    :error="form.errors.supplier_country"
                    large-label
                    :options="selectFromArray(countries)"
                />

                <RichText
                    ref="personal_data_en"
                    v-model="internalForm.personal_data_en"
                    :label="trans('tool.attributes.personal_data_en')"
                    :error="form.errors.personal_data_en"
                />

                <RichText
                    ref="personal_data_nl"
                    v-model="internalForm.personal_data_nl"
                    :label="trans('tool.attributes.personal_data_nl')"
                    :error="form.errors.personal_data_nl"
                />

                <TagInput
                    ref="data_processing_locations"
                    v-model="internalForm.data_processing_locations"
                    :label="trans('tool.attributes.data_processing_locations')"
                    :error="form.errors.data_processing_locations"
                    :available-tags="dataProcessingLocations"
                    large-label
                />

                <TextInput
                    ref="privacy_policy_url"
                    v-model="internalForm.privacy_policy_url"
                    name="privacy_policy_url"
                    :label="trans('tool.attributes.privacy_policy_url')"
                    :error="form.errors.privacy_policy_url"
                    large-label
                />

                <TextInput
                    ref="model_processor_agreement_url"
                    v-model="internalForm.model_processor_agreement_url"
                    name="model_processor_agreement_url"
                    :label="trans('tool.attributes.model_processor_agreement_url')"
                    :error="form.errors.model_processor_agreement_url"
                    large-label
                />

                <RichText
                    ref="privacy_analysis"
                    v-model="internalForm.privacy_analysis"
                    :label="trans('tool.attributes.privacy_analysis')"
                    :error="form.errors.privacy_analysis"
                />
            </FormColumn>

            <FormColumn>
                <CheckInlineInput
                    ref="supplier_agrees_with_surf_standards"
                    v-model="internalForm.supplier_agrees_with_surf_standards"
                    name="supplier_agrees_with_surf_standards"
                    :label="trans('tool.attributes.supplier_agrees_with_surf_standards')"
                    :error="form.errors.supplier_agrees_with_surf_standards"
                    large-label
                />

                <TagInput
                    ref="certifications"
                    v-model="internalForm.certifications"
                    :label="trans('tool.attributes.certifications')"
                    :error="form.errors.certification"
                    :available-tags="certifications"
                    large-label
                />

                <TextInput
                    ref="dtia_by_external_url"
                    v-model="internalForm.dtia_by_external_url"
                    name="dtia_by_external_url"
                    :label="trans('tool.attributes.dtia_by_external_url')"
                    :error="form.errors.dtia_by_external_url"
                    large-label
                />

                <TextInput
                    ref="dpia_by_external_url"
                    v-model="internalForm.dpia_by_external_url"
                    name="dpia_by_external_url"
                    :label="trans('tool.attributes.dpia_by_external_url')"
                    :error="form.errors.dpia_by_external_url"
                    large-label
                />

                <TextInput
                    ref="jurisdiction"
                    v-model="internalForm.jurisdiction"
                    name="jurisdiction"
                    :label="trans('tool.attributes.jurisdiction')"
                    :error="form.errors.jurisdiction"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup
            :title="trans('page.content-manager.tool.form.headings.support')"
            gray-background
        >
            <FormColumn first>
                <TextInput
                    ref="instructions_manual_1_url_en"
                    v-model="internalForm.instructions_manual_1_url_en"
                    name="instructions_manual_1_url_en"
                    :label="trans('tool.attributes.instructions_manual_1_url_en')"
                    :error="form.errors.instructions_manual_1_url_en"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_1_url_nl"
                    v-model="internalForm.instructions_manual_1_url_nl"
                    name="instructions_manual_1_url_nl"
                    :label="trans('tool.attributes.instructions_manual_1_url_nl')"
                    :error="form.errors.instructions_manual_1_url_nl"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_2_url_en"
                    v-model="internalForm.instructions_manual_2_url_en"
                    name="instructions_manual_2_url_en"
                    :label="trans('tool.attributes.instructions_manual_2_url_en')"
                    :error="form.errors.instructions_manual_2_url_en"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_2_url_nl"
                    v-model="internalForm.instructions_manual_2_url_nl"
                    name="instructions_manual_2_url_nl"
                    :label="trans('tool.attributes.instructions_manual_2_url_nl')"
                    :error="form.errors.instructions_manual_2_url_nl"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_3_url_en"
                    v-model="internalForm.instructions_manual_3_url_en"
                    name="instructions_manual_3_url_en"
                    :label="trans('tool.attributes.instructions_manual_3_url_en')"
                    :error="form.errors.instructions_manual_3_url_en"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_3_url_nl"
                    v-model="internalForm.instructions_manual_3_url_nl"
                    name="instructions_manual_3_url_nl"
                    :label="trans('tool.attributes.instructions_manual_3_url_nl')"
                    :error="form.errors.instructions_manual_3_url_nl"
                    large-label
                />

                <TextInput
                    ref="how_does_it_work_en"
                    v-model="internalForm.how_does_it_work_en"
                    name="how_does_it_work_en"
                    :label="trans('tool.attributes.how_does_it_work_en')"
                    :error="form.errors.how_does_it_work_en"
                    large-label
                />

                <TextInput
                    ref="how_does_it_work_nl"
                    v-model="internalForm.how_does_it_work_nl"
                    name="how_does_it_work_nl"
                    :label="trans('tool.attributes.how_does_it_work_nl')"
                    :error="form.errors.how_does_it_work_nl"
                    large-label
                />

                <RichText
                    ref="support_for_teachers_en"
                    v-model="internalForm.support_for_teachers_en"
                    :label="trans('tool.attributes.support_for_teachers_en')"
                    :error="form.errors.support_for_teachers_en"
                />

                <RichText
                    ref="support_for_teachers_nl"
                    v-model="internalForm.support_for_teachers_nl"
                    :label="trans('tool.attributes.support_for_teachers_nl')"
                    :error="form.errors.support_for_teachers_nl"
                />
            </FormColumn>

            <FormColumn>
                <RichText
                    ref="availability_surf"
                    v-model="internalForm.availability_surf"
                    :label="trans('tool.attributes.availability_surf')"
                    :error="form.errors.availability_surf"
                />

                <RichText
                    ref="accessibility_facilities_en"
                    v-model="internalForm.accessibility_facilities_en"
                    :label="trans('tool.attributes.accessibility_facilities_en')"
                    :error="form.errors.accessibility_facilities_en"
                />

                <RichText
                    ref="accessibility_facilities_nl"
                    v-model="internalForm.accessibility_facilities_nl"
                    :label="trans('tool.attributes.accessibility_facilities_nl')"
                    :error="form.errors.accessibility_facilities_nl"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.education')">
            <FormColumn first>
                <RichText
                    ref="use_for_education_en"
                    v-model="internalForm.use_for_education_en"
                    :label="trans('tool.attributes.use_for_education_en')"
                    :error="form.errors.use_for_education_en"
                />

                <RichText
                    ref="use_for_education_nl"
                    v-model="internalForm.use_for_education_nl"
                    :label="trans('tool.attributes.use_for_education_nl')"
                    :error="form.errors.use_for_education_nl"
                />
            </FormColumn>

            <FormColumn>
                <TagInput
                    ref="working_methods"
                    v-model="internalForm.working_methods"
                    :label="trans('tool.attributes.working_methods')"
                    :error="form.errors.working_methods"
                    :available-tags="workingMethods"
                    large-label
                />

                <TagInput
                    ref="target_groups"
                    v-model="internalForm.target_groups"
                    :label="trans('tool.attributes.target_groups')"
                    :error="form.errors.target_groups"
                    :available-tags="targetGroups"
                    large-label
                />

                <TagInput
                    ref="complexity"
                    v-model="internalForm.complexity"
                    :label="trans('tool.attributes.complexity')"
                    :error="form.errors.complexity"
                    :available-tags="complexities"
                    large-label
                />
            </FormColumn>
        </FormGroup>
    </div>
</template>

<script>
import formMixin from '@/mixins/form';

import FormGroup from '@/components/FormGroup';
import FormColumn from '@/components/FormColumn';
import CheckInlineInput from '@/components/form/CheckInlineInput';
import TextInput from '@/components/form/TextInput';
import FileInput from '@/components/form/FileInput';
import RichText from '@/components/form/RichText';
import SelectInput from '@/components/form/SelectInput.vue';
import { dateTime } from '@/helpers/datetime';
import InputDisplay from '@/components/form/InputDisplay.vue';
import { selectFromArray } from '@/helpers/input';
import TagInput from '@/components/form/TagInput.vue';

export default {
    components: {
        TagInput,
        InputDisplay,
        SelectInput,
        FormGroup,
        FormColumn,
        CheckInlineInput,
        TextInput,
        FileInput,
        RichText,
    },
    mixins: [formMixin],
    props: {
        tool: {
            type: Object,
            default: null,
        },
        features: {
            type: [Object, Array],
            required: true,
        },
        softwareTypes: {
            type: [Object, Array],
            required: true,
        },
        devices: {
            type: [Object, Array],
            required: true,
        },
        standards: {
            type: [Object, Array],
            required: true,
        },
        operatingSystems: {
            type: [Object, Array],
            required: true,
        },
        dataProcessingLocations: {
            type: [Object, Array],
            required: true,
        },
        certifications: {
            type: [Object, Array],
            required: true,
        },
        workingMethods: {
            type: [Object, Array],
            required: true,
        },
        targetGroups: {
            type: [Object, Array],
            required: true,
        },
        complexities: {
            type: [Object, Array],
            required: true,
        },
        countries: {
            type: Object,
            required: true,
        },
        updatedAt: {
            type: String,
            default: null,
        },
    },
    methods: { selectFromArray, dateTime },
};
</script>
