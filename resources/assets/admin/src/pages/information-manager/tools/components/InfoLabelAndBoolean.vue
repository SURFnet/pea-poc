<template>
    <div>
        <InputLabel large-label>
            {{ label }}
        </InputLabel>

        <div class="sm:text-sm">
            <span
                v-if="booleanIsPresent"
                v-text="numberAsBoolean ? trans('tool.attributes.is_true') : trans('tool.attributes.is_false')"
            />

            <span
                v-else
                class="italic"
                v-text="`(${trans('page.our.tool.show.none')})`"
            />
        </div>
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel';

export default {
    components: {
        InputLabel,
    },
    props: {
        label: {
            type: String,
            required: true,
        },
        numberAsBoolean: {
            type: Number,
            default: null,
        },
    },
    computed: {
        /**
         * @returns {boolean}
         */
        booleanIsPresent() {
            return this.numberAsBoolean !== null;
        },
    },
};
</script>
