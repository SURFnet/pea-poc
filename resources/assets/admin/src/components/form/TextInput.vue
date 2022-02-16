<template>
    <div>
        <InputLabel v-if="label && !placeholder" :for="id" :required="required" :large-label="largeLabel">
            {{ label }}
        </InputLabel>

        <input
            :id="id"
            ref="input"
            v-bind="$attrs"
            class="w-full block | rounded border-gray-300 sm:text-sm | focus:ring-blue-400 focus:border-blue-500 | mt-2"
            :class="inputClass"
            :type="type"
            :value="value"
            :required="required"
            :placeholder="placeholder"
            @input="$emit('input', $event.target.value)"
        />

        <InvalidFeedback v-if="error" :error="error" class="mt-2" />

        <HelpText :text="text" class="mt-2" />
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback';
import HelpText from '@/components/form/shared/HelpText';

export default {
    components: {
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
        placeholder: {
            type: String,
            default: null,
        },
        size: {
            type: String,
            default: 'md',
            /**
             * Validates the right type.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['md', 'lg'].indexOf(value) !== -1;
            },
        },
        largeLabel: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Determines the input class based on the current state.
         *
         * @returns {string}
         */
        inputClass() {
            const inputClasses = [];

            if (this.error) {
                inputClasses.push(
                    'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500'
                );
            }

            if (this.size === 'lg') {
                inputClasses.push('py-5 px-4');
            }

            return inputClasses.join(' | ');
        },
    },
    methods: {
        /**
         * Focuses the input.
         */
        focus() {
            this.$refs.input.focus();
        },
    },
};
</script>
