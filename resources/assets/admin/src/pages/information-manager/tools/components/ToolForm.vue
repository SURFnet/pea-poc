<template>
    <div class="md:px-8 md:pt-4">
        <h3
            class="text-xl font-semibold leading-6 text-gray-900 | mb-4"
            v-text="trans('page.information-manager.tool.form.headings.general')"
        />
        <p v-text="trans('page.information-manager.tool.form.marked-as-fit')" />
        <Btn
            class="mb-10"
            inertia
            :href="changeToProhibitedUrl"
            variant="default-dark"
            v-text="trans('page.information-manager.tool.form.change-to-unfit')"
        />
        <div class="mb-10">
            <RadioInputToolStatus
                ref="status"
                v-model="form.status"
                :options="selectFromArray(statusOptions)"
                :label="trans('institute.tool.attributes.status')"
                :error="form.errors.status"
            />
        </div>

        <FormGroup>
            <FormColumn first>
                <CheckGroupInput
                    ref="categories"
                    v-model="internalForm.categories"
                    name="categories"
                    :label="trans('institute.tool.attributes.categories', { institute: institute.short_name })"
                    :options="selectFromArray(categories)"
                    :error="form.errors.categories"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.information-manager.tool.form.headings.description')">
            <FormColumn first>
                <TextareaInput
                    ref="description_1"
                    v-model="internalForm.description_1"
                    name="description_1"
                    :label="trans('institute.tool.attributes.description_1')"
                    :error="form.errors.description_1"
                    large-label
                />
            </FormColumn>
            <FormColumn>
                <FileInput
                    ref="description_1_image_filename"
                    name="file"
                    :label="trans('institute.tool.attributes.description_1_image_filename')"
                    :error="internalForm.errors.description_1_image_filename"
                    :image-preview="internalForm.description_1_image_url"
                    large-label
                    :text="trans('page.information-manager.tool.form.captions.image-format')"
                    @change="(file) => (internalForm.description_1_image_filename = file)"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup>
            <FormColumn first>
                <TextareaInput
                    ref="description_2"
                    v-model="internalForm.description_2"
                    name="description_2"
                    :label="trans('institute.tool.attributes.description_2')"
                    :error="form.errors.description_2"
                    large-label
                />
            </FormColumn>
            <FormColumn>
                <FileInput
                    ref="description_2_image_filename"
                    name="file"
                    :label="trans('institute.tool.attributes.description_2_image_filename')"
                    :error="internalForm.errors.description_2_image_filename"
                    :image-preview="internalForm.description_2_image_url"
                    large-label
                    :text="trans('page.information-manager.tool.form.captions.image-format')"
                    @change="(file) => (internalForm.description_2_image_filename = file)"
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.information-manager.tool.form.headings.extra-information')">
            <FormColumn first>
                <TextInput
                    ref="extra_information_title"
                    v-model="internalForm.extra_information_title"
                    name="extra_information_title"
                    :label="trans('institute.tool.attributes.extra_information_title')"
                    :error="form.errors.extra_information_title"
                    large-label
                />

                <TextareaInput
                    ref="extra_information"
                    v-model="internalForm.extra_information"
                    name="extra_information"
                    :label="trans('institute.tool.attributes.extra_information')"
                    :error="form.errors.extra_information"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup :title="trans('page.information-manager.tool.form.headings.support')">
            <FormColumn first>
                <TextInput
                    ref="support_title_1"
                    v-model="internalForm.support_title_1"
                    name="support_title_1"
                    :label="trans('institute.tool.attributes.support_title_1')"
                    :error="form.errors.support_title_1"
                    large-label
                />

                <TextInput
                    ref="support_title_2"
                    v-model="internalForm.support_title_2"
                    name="support_title_2"
                    :label="trans('institute.tool.attributes.support_title_2')"
                    :error="form.errors.support_title_2"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TextInput
                    ref="support_email_1"
                    v-model="internalForm.support_email_1"
                    name="support_email_1"
                    :label="trans('institute.tool.attributes.support_email_1')"
                    :error="form.errors.support_email_1"
                    large-label
                />

                <TextInput
                    ref="support_email_2"
                    v-model="internalForm.support_email_2"
                    name="support_email_2"
                    :label="trans('institute.tool.attributes.support_email_2')"
                    :error="form.errors.support_email_2"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup>
            <FormColumn first class="mt-16 sm:mt-0">
                <TextInput
                    ref="manual_title_1"
                    v-model="internalForm.manual_title_1"
                    name="manual_title_1"
                    :label="trans('institute.tool.attributes.manual_title_1')"
                    :error="form.errors.manual_title_1"
                    large-label
                />

                <TextInput
                    ref="manual_title_2"
                    v-model="internalForm.manual_title_2"
                    name="manual_title_2"
                    :label="trans('institute.tool.attributes.manual_title_2')"
                    :error="form.errors.manual_title_2"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TextInput
                    ref="manual_url_1"
                    v-model="internalForm.manual_url_1"
                    name="manual_url_1"
                    :label="trans('institute.tool.attributes.manual_url_1')"
                    :error="form.errors.manual_url_1"
                    large-label
                />

                <TextInput
                    ref="manual_url_2"
                    v-model="internalForm.manual_url_2"
                    name="manual_url_2"
                    :label="trans('institute.tool.attributes.manual_url_2')"
                    :error="form.errors.manual_url_2"
                    large-label
                />
            </FormColumn>
        </FormGroup>

        <FormGroup>
            <FormColumn first class="mt-16 sm:mt-0">
                <TextInput
                    ref="video_title_1"
                    v-model="internalForm.video_title_1"
                    name="video_title_1"
                    :label="trans('institute.tool.attributes.video_title_1')"
                    :error="form.errors.video_title_1"
                    large-label
                />

                <TextInput
                    ref="video_title_2"
                    v-model="internalForm.video_title_2"
                    name="video_title_2"
                    :label="trans('institute.tool.attributes.video_title_2')"
                    :error="form.errors.video_title_2"
                    large-label
                />
            </FormColumn>

            <FormColumn>
                <TextInput
                    ref="video_url_1"
                    v-model="internalForm.video_url_1"
                    name="video_url_1"
                    :label="trans('institute.tool.attributes.video_url_1')"
                    :error="form.errors.video_url_1"
                    large-label
                />

                <TextInput
                    ref="video_url_2"
                    v-model="internalForm.video_url_2"
                    name="video_url_2"
                    :label="trans('institute.tool.attributes.video_url_2')"
                    :error="form.errors.video_url_2"
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
import FileInput from '@/components/form/FileInput';
import TextareaInput from '@/components/form/TextareaInput';
import CheckGroupInput from '@/components/form/CheckGroupInput';
import RadioInputToolStatus from '@/components/form/RadioInputToolStatus';
import Btn from '@/components/Btn';

import { selectFromArray } from '@/helpers/input';

export default {
    components: {
        FormGroup,
        FormColumn,
        CheckGroupInput,
        RadioInputToolStatus,
        TextInput,
        FileInput,
        TextareaInput,
        Btn,
    },
    mixins: [formMixin],
    props: {
        categories: {
            type: Object,
            required: true,
        },
        institute: {
            type: Object,
            required: true,
        },
        statusOptions: {
            type: Object,
            required: true,
        },
        changeToProhibitedUrl: {
            type: String,
            required: true,
        },
    },
    methods: {
        selectFromArray,
    },
};
</script>
