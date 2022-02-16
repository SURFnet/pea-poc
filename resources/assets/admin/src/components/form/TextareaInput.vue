<template>
    <div>
        <InputLabel v-if="label" :for="id" :required="required" :large-label="largeLabel">
            {{ label }}
        </InputLabel>

        <div class="mt-1">
            <textarea
                :id="id"
                ref="input"
                v-bind="$attrs"
                class="w-full block | rounded border-gray-300 sm:text-sm | focus:ring-blue-400 focus:border-blue-500 | mt-2"
                :class="inputClass"
                :type="type"
                :value="value"
                :required="required"
                rows="5"
                @input="$emit('input', $event.target.value)"
            />
        </div>

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
            required: false,

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
            required: false,
            default: 'text',
        },
        value: {
            type: String,
            required: false,
            default: null,
        },
        label: {
            type: String,
            required: false,
            default: null,
        },
        error: {
            type: String,
            default: null,
        },
        required: {
            type: Boolean,
            default: false,
            required: false,
        },
        text: {
            type: String,
            default: null,
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
    },
};
</script>
