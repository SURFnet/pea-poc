<template>
    <div>
        <FormGroup :title="trans('page.information-manager.category.form.headings.general')">
            <TextInput
                v-model="internalForm.name"
                name="name"
                :label="trans('category.attributes.name')"
                :error="form.errors.name"
                required
            />

            <TextareaInput
                v-model="internalForm.description"
                name="description"
                :label="trans('category.attributes.description')"
                :error="form.errors.description"
                required
                :text="trans('page.information-manager.category.form.captions.character-limit')"
            />
        </FormGroup>
    </div>
</template>

<script>
import formMixin from '@/mixins/form';

import FormGroup from '@/components/FormGroup';

import TextInput from '@/components/form/TextInput';
import TextareaInput from '@/components/form/TextareaInput';

import { selectFromArray } from '@/helpers/input';

export default {
    components: {
        FormGroup,
        TextInput,
        TextareaInput,
    },
    mixins: [formMixin],
    methods: {
        selectFromArray,
    },
};
</script>
