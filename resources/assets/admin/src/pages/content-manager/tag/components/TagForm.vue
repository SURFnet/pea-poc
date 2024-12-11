<template>
    <div>
        <FormGroup :title="trans('page.content-manager.tag.form.headings.general')">
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

                <SelectInput
                    v-model="internalForm.type"
                    class="w-full flex-shrink-1 | text-black"
                    :label="trans('tag.attributes.type')"
                    :options="selectFromArray(tagTypes)"
                    :error="internalForm.errors.type"
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
import SelectInput from '@/components/form/SelectInput.vue';
import { selectFromArray } from '@/helpers/input';

export default {
    components: {
        FormGroup,
        FormColumn,
        TextInput,
        SelectInput,
    },
    mixins: [formMixin],
    props: {
        tagTypes: {
            type: Object,
            required: true,
        },
    },
    methods: { selectFromArray },
};
</script>
