<template>
    <div>
        <InputLabel v-if="label" :for="id" :required="required">
            {{ label }}
        </InputLabel>

        <DatePicker
            :id="id"
            v-model="localValue"
            :input-attr="{ ...$attrs, ...{ required: required } }"
            :input-class="{
                ...inputClass,
                ...{
                    'w-full block | rounded border-gray-300 sm:text-sm | focus:ring-blue-400 focus:border-blue-500 | mt-1': true,
                },
            }"
            value-type="format"
            :format="format"
            @input="updateValue"
        />

        <InvalidFeedback v-if="error" :error="error" class="mt-2" />

        <HelpText :text="text" class="mt-2" />
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/en';

import InputLabel from '@/components/form/shared/InputLabel';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback';
import HelpText from '@/components/form/shared/HelpText';

export default {
    components: {
        DatePicker,

        InputLabel,
        InvalidFeedback,
        HelpText,
    },
    inheritAttrs: false,
    props: {
        id: {
            type: String,

            /**
             * Set a default id for the text input.
             *
             * @returns {string}
             */
            default() {
                // eslint-disable-next-line
                return `text-input-${this._uid}`;
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        value: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        error: {
            type: String,
            default: null,
        },
        required: {
            type: Boolean,
            default: false,
        },
        text: {
            type: String,
            default: null,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            format: 'DD-MM-YYYY',
            localValue: this.value,
        };
    },
    computed: {
        /**
         * Determines the input class based on the current state.
         *
         * @returns {string}
         */
        inputClass() {
            return {
                'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': this
                    .error,
            };
        },
    },
    methods: {
        /**
         * Focuses the input.
         */
        focus() {
            this.$refs.input.focus();
        },

        /**
         * Update with the the chosen date.
         *
         * @param {string} newValue
         */
        updateValue(newValue) {
            this.$emit('input', newValue);
        },
    },
};
</script>
