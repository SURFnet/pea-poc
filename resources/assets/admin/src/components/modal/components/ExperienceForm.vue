<template>
    <div class="space-y-6">
        <InputDisplay
            :label="trans('modal.shared.experience_form.experience_of')"
            :value="$page.props.currentUser.name"
        />

        <!-- eslint-disable vue/no-undef-properties -->
        <TextInput
            ref="title"
            v-model="internalForm.title"
            :label="trans('modal.shared.experience_form.title')"
            :error="form.errors.title"
            :required="true"
        />
        <!-- eslint-enable vue/no-undef-properties -->

        <TextareaInput
            v-model="internalForm.message"
            :label="trans('modal.shared.experience_form.message')"
            :error="form.errors.message"
            :text="trans('modal.shared.experience_form.info.max_length', { max: 800 })"
            :placeholder="trans('modal.shared.experience_form.info.placeholder')"
        />
    </div>
</template>

<script>
import formMixin from '@/mixins/form';

import InputDisplay from '@/components/form/InputDisplay';
import TextInput from '@/components/form/TextInput';
import TextareaInput from '@/components/form/TextareaInput';

export default {
    components: {
        InputDisplay,
        TextInput,
        TextareaInput,
    },
    mixins: [formMixin],
};
</script>
