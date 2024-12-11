<template>
    <div>
        <FormGroup :title="trans('page.information-manager.tag.form.headings.general')">
            <FormColumn>
                <!-- eslint-disable vue/no-undef-properties -->
                <TextInput
                    v-model="internalForm.name.en"
                    :label="trans('tag.attributes.name_en')"
                    :error="internalForm.errors?.['name.en']"
                    required
                />
                <!-- eslint-enable vue/no-undef-properties -->

                <TextInput
                    v-model="internalForm.name.nl"
                    :label="trans('tag.attributes.name_nl')"
                    :error="internalForm.errors?.['name.nl']"
                />

                <RichText
                    v-model="internalForm.description.en"
                    :label="trans('tag.attributes.description_en')"
                    :error="internalForm.errors?.['description.en']"
                />

                <RichText
                    v-model="internalForm.description.nl"
                    :label="trans('tag.attributes.description_nl')"
                    :error="internalForm.errors?.['description.nl']"
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
        FormGroup,
        FormColumn,
        TextInput,
    },
    mixins: [formMixin],
};
</script>
