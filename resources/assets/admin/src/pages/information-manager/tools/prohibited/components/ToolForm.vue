<template>
    <div class="md:px-8 md:pt-4">
        <h3
            class="text-xl font-semibold leading-6 text-gray-900 | mb-4"
            v-text="trans('page.information-manager.tool.form.headings.general')"
        />
        <p v-text="trans('page.information-manager.tool.form.marked-as-unfit')" />
        <Btn
            class="mb-10"
            inertia
            :href="changeToFitUrl"
            variant="default-dark"
            v-text="trans('page.information-manager.tool.form.change-to-fit')"
        />
        <TextareaInput
            v-model="form.why_unfit"
            name="why_unfit"
            :label="trans('institute.tool.attributes.why_unfit')"
            :error="internalForm.errors.why_unfit"
            large-label
        />
        <SelectInput
            v-if="alternativeTools"
            v-model="form.alternative_tool_id"
            class="w-full flex-shrink-1 | text-black"
            :label="trans('institute.tool.attributes.alternative_tool_id')"
            :options="selectFromArray(alternativeTools)"
            :error="internalForm.errors.alternative_tool_id"
        />

        <p v-else v-text="trans('institute.tool.no-alternative-tools')" />
    </div>
</template>

<script>
import formMixin from '@/mixins/form';

import TextareaInput from '@/components/form/TextareaInput';
import SelectInput from '@/components/form/SelectInput';
import Btn from '@/components/Btn';

import { selectFromArray } from '@/helpers/input';

export default {
    components: {
        TextareaInput,
        SelectInput,
        Btn,
    },
    mixins: [formMixin],
    props: {
        alternativeTools: {
            type: Object,
            default: null,
        },
        changeToFitUrl: {
            type: String,
            required: true,
        },
    },
    methods: {
        selectFromArray,
    },
};
</script>
