<template>
    <div class="md:px-8 md:pt-4">
        <FormGroup :title="trans('page.content-manager.tool.form.headings.general')">
            <FormColumn first>
                <FileInput
                    name="file"
                    :label="trans('tool.attributes.image_filename')"
                    :error="internalForm.errors.image_filename"
                    :image-preview="internalForm.image_url"
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.image-format-sm')"
                    @change="(file) => (internalForm.image_filename = file)"
                />

                <TextInput
                    v-model="internalForm.name"
                    name="name"
                    :label="trans('tool.attributes.name')"
                    :error="form.errors.name"
                    required
                    large-label
                    normal-weight
                />

                <TextareaInput
                    v-model="internalForm.description_short"
                    name="description_short"
                    :label="trans('tool.attributes.description_short')"
                    :error="form.errors.description_short"
                    required
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.short-description')"
                />
            </FormColumn>
            <FormColumn class="md:col-span-1 | mt-6 md:mt-0">
                <CheckGroupInput
                    v-model="internalForm.features"
                    name="features"
                    :label="trans('feature.plural')"
                    :options="selectFromArray(features)"
                    :error="form.errors.features"
                    large-label
                    normal-weight
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.technical')" gray-background>
            <FormColumn first>
                <CheckGroupInput
                    v-model="internalForm.supported_standards"
                    name="supported_standards"
                    :label="trans('tool.attributes.supported_standards')"
                    :options="selectFromArray(supportedStandards)"
                    :error="form.errors.supported_standards"
                    large-label
                />

                <TextInput
                    v-model="internalForm.additional_standards"
                    name="additional_standards"
                    :label="trans('tool.attributes.additional_standards')"
                    :error="form.errors.additional_standards"
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.use-comma')"
                />
            </FormColumn>
            <FormColumn>
                <CheckGroupInput
                    v-model="internalForm.authentication_methods"
                    class="mb-4"
                    name="authentication_methods"
                    :label="trans('tool.attributes.authentication_methods')"
                    :options="selectFromArray(authenticationMethods)"
                    :error="form.errors.authentication_methods"
                    large-label
                />

                <CheckGroupInput
                    v-model="internalForm.stored_data"
                    class="mb-2"
                    name="stored_data"
                    :label="trans('tool.attributes.stored_data')"
                    :options="selectFromArray(storedData)"
                    :error="form.errors.stored_data"
                    large-label
                />

                <div class="w-full | flex flex-col">
                    <p class="text-sm font-medium text-gray-700 | flex-shrink-0">
                        {{ trans('tool.attributes.other_stored_data') }}
                    </p>
                    <TextInput
                        v-model="internalForm.other_stored_data"
                        class="w-full | mb-1"
                        name="other_stored_data"
                        :error="form.errors.other_stored_data"
                        large-label
                    />
                </div>
                <span
                    class="flex-shrink-0 text-sm text-gray-500 | mb-4"
                    v-text="trans('page.content-manager.tool.form.captions.use-comma')"
                />

                <CheckInlineInput
                    v-model="internalForm.european_data_storage"
                    class="mb-4"
                    name="european_data_storage"
                    :label="trans('tool.attributes.european_data_storage')"
                    :error="form.errors.european_data_storage"
                    large-label
                />

                <CheckInlineInput
                    v-model="internalForm.surf_standards_framework_agreed"
                    class="mb-4"
                    name="surf_standards_framework_agreed"
                    :label="trans('tool.attributes.surf_standards_framework_agreed')"
                    :error="form.errors.surf_standards_framework_agreed"
                    large-label
                />

                <CheckInlineInput
                    v-model="internalForm.has_processing_agreement"
                    name="has_processing_agreement"
                    :label="trans('tool.attributes.has_processing_agreement')"
                    :error="form.errors.has_processing_agreement"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.content-manager.tool.form.headings.description')">
            <FormColumn first>
                <TextareaInput
                    v-model="internalForm.description_long_1"
                    name="description_long_1"
                    :label="trans('tool.attributes.description_long_1')"
                    :error="form.errors.description_long_1"
                    large-label
                />
            </FormColumn>
            <FormColumn>
                <FileInput
                    name="file"
                    :label="trans('tool.attributes.description_1_image_filename')"
                    :error="internalForm.errors.description_1_image_filename"
                    :image-preview="internalForm.description_1_image_url"
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.image-format-lg')"
                    @change="(file) => (internalForm.description_1_image_filename = file)"
                />
            </FormColumn>
        </FormGroup>
        <FormGroup>
            <FormColumn first>
                <TextareaInput
                    v-model="internalForm.description_long_2"
                    name="description_long_2"
                    :label="trans('tool.attributes.description_long_2')"
                    :error="form.errors.description_long_2"
                    large-label
                />
            </FormColumn>
            <FormColumn>
                <FileInput
                    name="file"
                    :label="trans('tool.attributes.description_2_image_filename')"
                    :error="internalForm.errors.description_2_image_filename"
                    :image-preview="internalForm.description_2_image_url"
                    large-label
                    :text="trans('page.content-manager.tool.form.captions.image-format-lg')"
                    @change="(file) => (internalForm.description_2_image_filename = file)"
                />
            </FormColumn>
        </FormGroup>
        <FormGroup>
            <FormColumn first>
                <TextInput
                    v-model="internalForm.info_url"
                    name="info_url"
                    :label="trans('tool.attributes.info_url')"
                    :error="form.errors.info_url"
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
import TextareaInput from '@/components/form/TextareaInput';
import CheckGroupInput from '@/components/form/CheckGroupInput';

import { selectFromArray } from '@/helpers/input';

export default {
    components: {
        FormGroup,
        FormColumn,
        CheckGroupInput,
        CheckInlineInput,
        TextInput,
        FileInput,
        TextareaInput,
    },
    mixins: [formMixin],
    props: {
        features: {
            type: Object,
            required: true,
        },
        authenticationMethods: {
            type: Object,
            required: true,
        },
        storedData: {
            type: Object,
            required: true,
        },
        supportedStandards: {
            type: Object,
            required: true,
        },
    },
    methods: {
        selectFromArray,
    },
};
</script>
