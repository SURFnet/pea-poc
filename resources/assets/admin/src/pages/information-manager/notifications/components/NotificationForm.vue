<template>
    <div>
        <div class="flex flex-col gap-4">
            <!-- eslint-disable vue/no-undef-properties -->
            <SelectInput
                v-model="internalForm.tool"
                :label="trans('institute.notifications.attributes.tool')"
                :placeholder="trans('page.information-manager.notifications.form.tool')"
                :error="internalForm.errors.tool"
                :options="selectFromArray(tools)"
                :clearable="false"
                required
            />
            <!-- eslint-enable vue/no-undef-properties -->

            <TextInput
                v-model="internalForm.subject"
                :label="trans('institute.notifications.attributes.subject')"
                :error="internalForm.errors.subject"
                required
            />

            <TextareaInput
                v-model="internalForm.message"
                :label="trans('institute.notifications.attributes.message')"
                :error="internalForm.errors.message"
                required
            />
        </div>
    </div>
</template>
<script>
import formMixin from '@/mixins/form';

import TextInput from '@/components/form/TextInput';
import TextareaInput from '@/components/form/TextareaInput';
import SelectInput from '@/components/form/SelectInput';

import { selectFromArray } from '@/helpers/input';

export default {
    components: {
        SelectInput,
        TextInput,
        TextareaInput,
    },
    mixins: [formMixin],
    props: {
        tools: {
            type: Object,
            required: true,
        },
    },
    methods: {
        selectFromArray,
    },
};
</script>
