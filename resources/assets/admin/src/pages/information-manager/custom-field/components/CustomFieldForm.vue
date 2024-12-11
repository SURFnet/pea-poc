<template>
    <div>
        <FormGroup :title="trans('page.information-manager.custom-field.form.headings.general')">
            <FormColumn>
                <!-- eslint-disable vue/no-undef-properties -->
                <TextInput
                    v-model="internalForm.title_en"
                    name="title_en"
                    :label="trans('custom-field.attributes.title_en')"
                    :error="form.errors.title_en"
                    required
                />
                <!-- eslint-enable vue/no-undef-properties -->

                <TextInput
                    v-model="internalForm.title_nl"
                    name="title_nl"
                    :label="trans('custom-field.attributes.title_nl')"
                    :error="form.errors.title_nl"
                />

                <NumberInput
                    v-model="internalForm.sortkey"
                    name="sortkey"
                    :label="trans('custom-field.attributes.sortkey')"
                    :error="form.errors.sortkey"
                    :min="1"
                    :max="20"
                    :step="1"
                />

                <SelectInput
                    v-model="internalForm.tab_type"
                    class="w-full flex-shrink-1 | text-black"
                    :label="trans('custom-field.attributes.tab_type')"
                    :options="selectFromArray(tabTypes)"
                    :error="internalForm.errors.tab_type"
                    required
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
import SelectInput from '@/components/form/SelectInput.vue';
import { selectFromArray } from '@/helpers/input';
import NumberInput from '@/components/form/NumberInput.vue';

export default {
    components: {
        NumberInput,
        SelectInput,
        FormGroup,
        FormColumn,
        TextInput,
    },
    mixins: [formMixin],
    props: {
        tabTypes: {
            type: Object,
            required: true,
        },
    },
    methods: { selectFromArray },
};
</script>
