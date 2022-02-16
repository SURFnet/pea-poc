<template>
    <div class="space-y-6">
        <SelectStarRating
            v-model="internalForm.rating"
            :label="trans('modal.shared.experience_form.select_stars')"
            :error="form.errors.rating"
        />

        <InputDisplay
            :label="trans('modal.shared.experience_form.experience_of')"
            :value="$page.props.currentUser.name"
        />

        <TextInput
            ref="title"
            v-model="internalForm.title"
            :label="trans('modal.shared.experience_form.title')"
            :error="form.errors.title"
        />

        <TextareaInput
            v-model="internalForm.message"
            :label="trans('modal.shared.experience_form.message')"
            :error="form.errors.message"
            :text="trans('modal.shared.experience_form.info.message', { max: 800 })"
        />
    </div>
</template>

<script>
import formMixin from '@/mixins/form';

import SelectStarRating from '@/components/form/SelectStarRating';
import InputDisplay from '@/components/form/InputDisplay';
import TextInput from '@/components/form/TextInput';
import TextareaInput from '@/components/form/TextareaInput';

export default {
    components: {
        SelectStarRating,
        InputDisplay,
        TextInput,
        TextareaInput,
    },
    mixins: [formMixin],
};
</script>
