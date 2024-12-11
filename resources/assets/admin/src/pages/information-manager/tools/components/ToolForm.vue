<template>
    <div class="md:px-8 md:pt-4">
        <h3
            class="text-xl font-semibold leading-6 text-gray-900 | mb-4"
            v-text="trans('page.information-manager.tool.form.headings.product')"
        />

        <p class="text-gray-900 | pb-5">
            {{ trans('page.information-manager.tool.form.marked-as-fit') }}

            <ToolTip
                v-if="markedAsFitTooltip"
                :text="markedAsFitTooltip"
            />
        </p>

        <div class="mb-10">
            <!-- eslint-disable vue/no-undef-properties -->
            <RadioInputToolStatus
                ref="status"
                v-model="form.status"
                :options="selectFromArray(statusOptions)"
                :label="trans('institute.tool.attributes.status')"
                :error="form.errors.status"
                :tool-tip="trans('institute.tool.tooltip.status')"
                large-label
            />
            <!-- eslint-enable vue/no-undef-properties -->
        </div>

        <FormGroup>
            <FormColumn first>
                <!-- eslint-disable vue/no-undef-properties -->
                <TagInput
                    ref="categories"
                    v-model="internalForm.categories"
                    :label="trans('institute.tool.attributes.categories')"
                    :error="form.errors.categories"
                    :available-tags="categories"
                    :tool-tip="trans('institute.tool.tooltip.categories')"
                    large-label
                />
                <!-- eslint-enable vue/no-undef-properties -->

                <template v-if="disallowed">
                    <RichText
                        v-model="internalForm.why_unfit_en"
                        :label="trans('institute.tool.attributes.why_unfit_en')"
                        :error="internalForm.errors.why_unfit_en"
                        :tool-tip="trans('institute.tool.tooltip.why_unfit_en')"
                    />
                    <!-- eslint-enable vue/no-undef-properties -->

                    <RichText
                        v-model="internalForm.why_unfit_nl"
                        :label="trans('institute.tool.attributes.why_unfit_nl')"
                        :error="internalForm.errors.why_unfit_nl"
                        :tool-tip="trans('institute.tool.tooltip.why_unfit_nl')"
                    />
                </template>

                <RichText
                    ref="conditions_en"
                    v-model="internalForm.conditions_en"
                    name="conditions_en"
                    :label="trans('institute.tool.attributes.conditions_en')"
                    :error="form.errors.conditions_en"
                    :tool-tip="trans('institute.tool.tooltip.conditions_en')"
                    large-label
                />

                <RichText
                    ref="conditions_nl"
                    v-model="internalForm.conditions_nl"
                    name="conditions_nl"
                    :label="trans('institute.tool.attributes.conditions_nl')"
                    :error="form.errors.conditions_nl"
                    :tool-tip="trans('institute.tool.tooltip.conditions_nl')"
                    large-label
                />

                <div>
                    <TagInput
                        ref="alternative_tools_ids"
                        v-model="internalForm.alternative_tools_ids"
                        :label="trans('institute.tool.attributes.alternative_tools')"
                        :error="form.errors.alternative_tools_ids"
                        :available-tags="alternativeTools"
                        :tool-tip="trans('institute.tool.tooltip.alternative_tools')"
                        large-label
                    />

                    <div
                        v-if="prohibitedAlternativeTools?.length"
                        class="space-y-2 | mt-2 mx-4 | rounded | p-4 bg-slate-50"
                    >
                        <span v-text="trans('institute.tool.attributes.prohibited_tools')" />

                        <ul>
                            <li
                                v-for="prohibitedAlternativeTool in prohibitedAlternativeTools"
                                :key="prohibitedAlternativeTool.id"
                                v-text="prohibitedAlternativeTool.name"
                            />
                        </ul>
                    </div>
                </div>

                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'product')"
                    :key="`en_${field.id}`"
                    :ref="`custom_field_${field.id}_en`"
                    v-model="field.value_en"
                    :name="`custom_field_${field.id}_en`"
                    :label="translatedCustomFieldTitle(field, 'en')"
                    :error="form.errors.custom_fields"
                    large-label
                />

                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'product')"
                    :key="`nl_${field.id}`"
                    :ref="`custom_field_${field.id}_nl`"
                    v-model="field.value_nl"
                    :name="`custom_field_${field.id}_nl`"
                    :label="translatedCustomFieldTitle(field, 'nl')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup
            :title="trans('page.information-manager.tool.form.headings.technical')"
            gray-background
        >
            <FormColumn first>
                <RichText
                    ref="links_with_other_tools_en"
                    v-model="internalForm.links_with_other_tools_en"
                    :label="trans('institute.tool.attributes.links_with_other_tools_en')"
                    :error="form.errors.links_with_other_tools_en"
                    :tool-tip="trans('institute.tool.tooltip.links_with_other_tools_en')"
                    large-label
                />

                <RichText
                    ref="links_with_other_tools_nl"
                    v-model="internalForm.links_with_other_tools_nl"
                    :label="trans('institute.tool.attributes.links_with_other_tools_nl')"
                    :error="form.errors.links_with_other_tools_nl"
                    :tool-tip="trans('institute.tool.tooltip.links_with_other_tools_nl')"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TextInput
                    ref="sla_url"
                    v-model="internalForm.sla_url"
                    name="sla_url"
                    :label="trans('institute.tool.attributes.sla_url')"
                    :error="form.errors.sla_url"
                    :tool-tip="trans('institute.tool.tooltip.sla_url')"
                    large-label
                />
            </FormColumn>

            <FormColumn first>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'technical')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_en`"
                    v-model="field.value_en"
                    :name="`custom_field_${field.id}_en`"
                    :label="translatedCustomFieldTitle(field, 'en')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'technical')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_nl`"
                    v-model="field.value_nl"
                    :name="`custom_field_${field.id}_nl`"
                    :label="translatedCustomFieldTitle(field, 'nl')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.information-manager.tool.form.headings.privacy_and_security')">
            <FormColumn first>
                <TextInput
                    ref="privacy_contact"
                    v-model="internalForm.privacy_contact"
                    name="privacy_contact"
                    :label="trans('institute.tool.attributes.privacy_contact')"
                    :error="form.errors.privacy_contact"
                    :tool-tip="trans('institute.tool.tooltip.privacy_contact')"
                    large-label
                />

                <TextInput
                    ref="privacy_evaluation_url"
                    v-model="internalForm.privacy_evaluation_url"
                    name="privacy_evaluation_url"
                    :label="trans('institute.tool.attributes.privacy_evaluation_url')"
                    :error="form.errors.privacy_evaluation_url"
                    :tool-tip="trans('institute.tool.tooltip.privacy_evaluation_url')"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TextInput
                    ref="security_evaluation_url"
                    v-model="internalForm.security_evaluation_url"
                    name="security_evaluation_url"
                    :label="trans('institute.tool.attributes.security_evaluation_url')"
                    :error="form.errors.security_evaluation_url"
                    :tool-tip="trans('institute.tool.tooltip.security_evaluation_url')"
                    large-label
                />

                <SelectInput
                    ref="data_classification"
                    v-model="internalForm.data_classification"
                    class="w-full flex-shrink-1 | text-black"
                    :label="trans('institute.tool.attributes.data_classification')"
                    :options="selectFromArray(dataClassifications)"
                    :error="internalForm.errors.data_classification"
                    :tool-tip="trans('institute.tool.tooltip.data_classification')"
                />
            </FormColumn>

            <FormColumn first>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'privacy_and_security')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_en`"
                    v-model="field.value_en"
                    :name="`custom_field_${field.id}_en`"
                    :label="translatedCustomFieldTitle(field, 'en')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'privacy_and_security')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_nl`"
                    v-model="field.value_nl"
                    :name="`custom_field_${field.id}_nl`"
                    :label="translatedCustomFieldTitle(field, 'nl')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup
            :title="trans('page.information-manager.tool.form.headings.support')"
            gray-background
        >
            <FormColumn first>
                <TextInput
                    ref="how_to_login_en"
                    v-model="internalForm.how_to_login_en"
                    name="how_to_login_en"
                    :label="trans('institute.tool.attributes.how_to_login_en')"
                    :error="form.errors.how_to_login_en"
                    :tool-tip="trans('institute.tool.tooltip.how_to_login_en')"
                    large-label
                />

                <TextInput
                    ref="how_to_login_nl"
                    v-model="internalForm.how_to_login_nl"
                    name="how_to_login_nl"
                    :label="trans('institute.tool.attributes.how_to_login_nl')"
                    :error="form.errors.how_to_login_nl"
                    :tool-tip="trans('institute.tool.tooltip.how_to_login_nl')"
                    large-label
                />

                <TextInput
                    ref="availability_en"
                    v-model="internalForm.availability_en"
                    name="availability_en"
                    :label="trans('institute.tool.attributes.availability_en')"
                    :error="form.errors.availability_en"
                    :tool-tip="trans('institute.tool.tooltip.availability_en')"
                    large-label
                />

                <TextInput
                    ref="availability_nl"
                    v-model="internalForm.availability_nl"
                    name="availability_nl"
                    :label="trans('institute.tool.attributes.availability_nl')"
                    :error="form.errors.availability_nl"
                    :tool-tip="trans('institute.tool.tooltip.availability_nl')"
                    large-label
                />

                <TextInput
                    ref="licensing_en"
                    v-model="internalForm.licensing_en"
                    name="licensing_en"
                    :label="trans('institute.tool.attributes.licensing_en')"
                    :error="form.errors.licensing_en"
                    :tool-tip="trans('institute.tool.tooltip.licensing_en')"
                    large-label
                />

                <TextInput
                    ref="licensing_nl"
                    v-model="internalForm.licensing_nl"
                    name="licensing_nl"
                    :label="trans('institute.tool.attributes.licensing_nl')"
                    :error="form.errors.licensing_nl"
                    :tool-tip="trans('institute.tool.tooltip.licensing_nl')"
                    large-label
                />

                <RichText
                    ref="request_access_en"
                    v-model="internalForm.request_access_en"
                    name="request_access_en"
                    :label="trans('institute.tool.attributes.request_access_en')"
                    :error="form.errors.request_access_en"
                    :tool-tip="trans('institute.tool.tooltip.request_access_en')"
                    large-label
                />

                <RichText
                    ref="request_access_nl"
                    v-model="internalForm.request_access_nl"
                    name="request_access_nl"
                    :label="trans('institute.tool.attributes.request_access_nl')"
                    :error="form.errors.request_access_nl"
                    :tool-tip="trans('institute.tool.tooltip.request_access_nl')"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <RichText
                    ref="instructions_en"
                    v-model="internalForm.instructions_en"
                    :label="trans('institute.tool.attributes.instructions_en')"
                    :error="form.errors.instructions_en"
                    :tool-tip="trans('institute.tool.tooltip.instructions_en')"
                    large-label
                />

                <RichText
                    ref="instructions_nl"
                    v-model="internalForm.instructions_nl"
                    :label="trans('institute.tool.attributes.instructions_nl')"
                    :error="form.errors.instructions_nl"
                    :tool-tip="trans('institute.tool.tooltip.instructions_nl')"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_1_url"
                    v-model="internalForm.instructions_manual_1_url"
                    name="instructions_manual_1_url"
                    :label="trans('institute.tool.attributes.instructions_manual_1_url')"
                    :error="form.errors.instructions_manual_1_url"
                    :tool-tip="trans('institute.tool.tooltip.instructions_manual_1_url')"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_2_url"
                    v-model="internalForm.instructions_manual_2_url"
                    name="instructions_manual_2_url"
                    :label="trans('institute.tool.attributes.instructions_manual_2_url')"
                    :error="form.errors.instructions_manual_2_url"
                    :tool-tip="trans('institute.tool.tooltip.instructions_manual_2_url')"
                    large-label
                />

                <TextInput
                    ref="instructions_manual_3_url"
                    v-model="internalForm.instructions_manual_3_url"
                    name="instructions_manual_3_url"
                    :label="trans('institute.tool.attributes.instructions_manual_3_url')"
                    :error="form.errors.instructions_manual_3_url"
                    :tool-tip="trans('institute.tool.tooltip.instructions_manual_3_url')"
                    large-label
                />
            </FormColumn>

            <FormColumn first>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'support')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_en`"
                    v-model="field.value_en"
                    :name="`custom_field_${field.id}_en`"
                    :label="translatedCustomFieldTitle(field, 'en')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'support')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_nl`"
                    v-model="field.value_nl"
                    :name="`custom_field_${field.id}_nl`"
                    :label="translatedCustomFieldTitle(field, 'nl')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.information-manager.tool.form.headings.education')">
            <FormColumn first>
                <RichText
                    ref="faq_en"
                    v-model="internalForm.faq_en"
                    :label="trans('institute.tool.attributes.faq_en')"
                    :error="form.errors.faq_en"
                    :tool-tip="trans('institute.tool.tooltip.faq_en')"
                    large-label
                />

                <RichText
                    ref="faq_nl"
                    v-model="internalForm.faq_nl"
                    :label="trans('institute.tool.attributes.faq_nl')"
                    :error="form.errors.faq_nl"
                    :tool-tip="trans('institute.tool.tooltip.faq_nl')"
                    large-label
                />

                <RichText
                    ref="examples_of_usage_en"
                    v-model="internalForm.examples_of_usage_en"
                    :label="trans('institute.tool.attributes.examples_of_usage_en')"
                    :error="form.errors.examples_of_usage_en"
                    :tool-tip="trans('institute.tool.tooltip.examples_of_usage_en')"
                    large-label
                />

                <RichText
                    ref="examples_of_usage_nl"
                    v-model="internalForm.examples_of_usage_nl"
                    :label="trans('institute.tool.attributes.examples_of_usage_nl')"
                    :error="form.errors.examples_of_usage_nl"
                    :tool-tip="trans('institute.tool.tooltip.examples_of_usage_nl')"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TextInput
                    ref="additional_info_heading_en"
                    v-model="internalForm.additional_info_heading_en"
                    name="additional_info_heading_en"
                    :label="trans('institute.tool.attributes.additional_info_heading_en')"
                    :error="form.errors.additional_info_heading_en"
                    :tool-tip="trans('institute.tool.tooltip.additional_info_heading_en')"
                    large-label
                />

                <TextInput
                    ref="additional_info_heading_nl"
                    v-model="internalForm.additional_info_heading_nl"
                    name="additional_info_heading_nl"
                    :label="trans('institute.tool.attributes.additional_info_heading_nl')"
                    :error="form.errors.additional_info_heading_nl"
                    :tool-tip="trans('institute.tool.tooltip.additional_info_heading_nl')"
                    large-label
                />

                <RichText
                    ref="additional_info_text_en"
                    v-model="internalForm.additional_info_text_en"
                    :label="trans('institute.tool.attributes.additional_info_text_en')"
                    :error="form.errors.additional_info_text_en"
                    :tool-tip="trans('institute.tool.tooltip.additional_info_text_en')"
                    large-label
                />

                <RichText
                    ref="additional_info_text_nl"
                    v-model="internalForm.additional_info_text_nl"
                    :label="trans('institute.tool.attributes.additional_info_text_nl')"
                    :error="form.errors.additional_info_text_nl"
                    :tool-tip="trans('institute.tool.tooltip.additional_info_text_nl')"
                    large-label
                />
            </FormColumn>

            <FormColumn first>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'education')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_en`"
                    v-model="field.value_en"
                    :name="`custom_field_${field.id}_en`"
                    :label="translatedCustomFieldTitle(field, 'en')"
                    :error="form.errors.custom_fields"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <RichText
                    v-for="field in filterCustomFields(internalForm.custom_fields, 'education')"
                    :key="field.id"
                    :ref="`custom_field_${field.id}_nl`"
                    v-model="field.value_nl"
                    :name="`custom_field_${field.id}_nl`"
                    :label="translatedCustomFieldTitle(field, 'nl')"
                    :error="form.errors.custom_fields"
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
import TextInput from '@/components/form/TextInput';
import RadioInputToolStatus from '@/components/form/RadioInputToolStatus';
import RichText from '@/components/form/RichText';

import { selectFromArray } from '@/helpers/input';
import ToolTip from '@/components/ToolTip.vue';
import SelectInput from '@/components/form/SelectInput.vue';
import TagInput from '@/components/form/TagInput.vue';
import { filterCustomFields } from '@/helpers/filter-custom-fields';

export default {
    components: {
        TagInput,
        SelectInput,
        ToolTip,
        FormGroup,
        FormColumn,
        RadioInputToolStatus,
        TextInput,
        RichText,
    },
    mixins: [formMixin],
    props: {
        alternativeTools: {
            type: [Object, Array],
            required: true,
        },
        prohibitedAlternativeTools: {
            type: [Object, Array],
            default: null,
        },
        categories: {
            type: [Object, Array],
            required: true,
        },
        dataClassifications: {
            type: [Object, Array],
            required: true,
        },
        statusOptions: {
            type: Object,
            required: true,
        },
    },
    computed: {
        /**
         * @returns {string}
         */
        markedAsFitTooltip() {
            return trans('institute.tool.tooltip.marked_as_fit');
        },
        /**
         * @returns {boolean}
         */
        disallowed() {
            // eslint-disable-next-line vue/no-undef-properties
            return this.form.status === 'disallowed';
        },
    },
    methods: {
        selectFromArray,
        filterCustomFields,

        /**
         * @param {object} field
         * @param {string} locale
         *
         * @returns {string}
         */
        translatedCustomFieldTitle(field, locale) {
            const title = field[`title_${locale}`] || field.title_en;

            return `${title} (${locale.toUpperCase()})`;
        },
    },
};
</script>
