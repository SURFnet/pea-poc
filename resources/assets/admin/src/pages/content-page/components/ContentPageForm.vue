<template>
    <div>
        <FormGroup :title="trans('page.content-page.form.headings.general')">
            <FormColumn first>
                <!-- eslint-disable vue/no-undef-properties -->
                <TextInput
                    v-model="internalForm.title_en"
                    :label="trans('content-page.attributes.title_en')"
                    :error="internalForm.errors.title_en"
                    required
                />
                <!-- eslint-enable vue/no-undef-properties -->

                <TextInput
                    v-model="internalForm.title_nl"
                    :label="trans('content-page.attributes.title_nl')"
                    :error="internalForm.errors.title_nl"
                />

                <TextInput
                    v-model="internalForm.slug"
                    :label="trans('content-page.attributes.slug')"
                    :error="internalForm.errors.slug"
                    :tool-tip="trans('content-page.tool_tips.slug')"
                    required
                />

                <RichText
                    v-model="internalForm.body_en"
                    :label="trans('content-page.attributes.body_en')"
                    :error="internalForm.errors.body_en"
                    required
                />

                <RichText
                    v-model="internalForm.body_nl"
                    :label="trans('content-page.attributes.body_nl')"
                    :error="internalForm.errors.body_nl"
                />
            </FormColumn>
        </FormGroup>
    </div>
</template>

<script>
import formMixin from '@/mixins/form';

import FormGroup from '@/components/FormGroup.vue';
import FormColumn from '@/components/FormColumn.vue';
import TextInput from '@/components/form/TextInput.vue';
import RichText from '@/components/form/RichText.vue';

export default {
    components: {
        RichText,
        TextInput,
        FormColumn,
        FormGroup,
    },
    mixins: [formMixin],
};
</script>
