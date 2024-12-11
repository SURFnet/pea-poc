<template>
    <div>
        <InputLabel
            v-if="label"
            :for="id"
            :required="required"
        >
            {{ label }}
        </InputLabel>

        <input
            :id="id"
            ref="input"
            v-bind="$attrs"
            class="w-full block | rounded border-gray-300 sm:text-sm | focus:ring-blue-400 focus:border-blue-500 | mt-1"
            :class="inputClass"
            type="number"
            :value="value"
            :required="required"
            @input="$emit('input', $event.target.value)"
        />

        <InvalidFeedback
            v-if="error"
            :error="error"
            class="mt-2"
        />

        <HelpText
            :text="text"
            class="mt-2"
        />
    </div>
</template>

<script>
import uniqueId from 'lodash/uniqueId';

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
             * Set a default id for the number input.
             *
             * @returns {string}
             */
            default() {
                return `number-input-${uniqueId()}`;
            },
        },
        value: {
            type: [String, Number],
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
    computed: {
        /**
         * Determines the input class based on the current state.
         *
         * @returns {string}
         */
        inputClass() {
            return {
                'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500':
                    this.error,
            };
        },
    },
};
</script>
