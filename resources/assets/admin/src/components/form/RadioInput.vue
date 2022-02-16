<template>
    <div>
        <InputLabel v-if="label" :required="required">
            {{ label }}
        </InputLabel>

        <div class="mt-4 space-y-4">
            <div v-for="item in options" :key="item.value" class="flex items-center">
                <input
                    :id="`radio-input-${item.value}`"
                    ref="input"
                    :value="item.value"
                    type="radio"
                    v-bind="$attrs"
                    class="h-4 w-4 | text-blue-600 border-gray-300 | focus:ring-blue-400"
                    :class="inputClass"
                    :checked="value == item.value"
                    @change="$emit('input', $event.target.value)"
                />

                <label
                    :for="`radio-input-${item.value}`"
                    class="ml-3 block text-sm font-medium text-gray-700"
                    v-text="item.label"
                />
            </div>
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

            /**
             * Set a default id for the check input.
             *
             * @returns {string}
             */
            default() {
                // eslint-disable-next-line
                return `radio-input-${this._uid}`;
            },
        },
        value: {
            type: [Number, String, Boolean, Array],
            default: null,
        },
        checkedValue: {
            type: [Number, String, Boolean, Array],
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        options: {
            type: Array,
            default: () => [],
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
                'border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500': this.error,
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
