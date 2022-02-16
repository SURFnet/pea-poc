<template>
    <div class="flex items-start">
        <div class="h-5 flex items-center">
            <input
                :id="id"
                ref="input"
                v-model="localValue"
                type="checkbox"
                v-bind="$attrs"
                class="h-4 w-4 | rounded | text-blue-600 border-gray-300 | focus:ring-blue-400"
                :class="inputClass"
                @change="updateValue"
            />
        </div>
        <div class="ml-3 text-sm">
            <InputLabel
                v-if="label"
                :for="id"
                :required="required"
                class="inline-flex items-center"
                :large-label="largeLabel"
            >
                {{ label }}
            </InputLabel>

            <InvalidFeedback v-if="error" :error="error" class="mt-2" />

            <HelpText :text="text" class="mt-2" />
        </div>
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
                return `check-input-${this._uid}`;
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
        largeLabel: {
            type: Boolean,
            default: false,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
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
                'border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500': this.error,
            };
        },
    },
    watch: {
        /**
         * Handles setting the given value on load.
         *
         * @param {number|string|boolean|Array} newValue
         */
        value(newValue) {
            this.localValue = newValue;
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
         * Update with the the chosen option.
         *
         * @param {object} event
         */
        updateValue(event) {
            const isChecked = event.target.checked;
            let emitValue = null;

            if (this.checkedValue && isChecked) {
                emitValue = this.checkedValue;
            }

            if (!this.checkedValue) {
                emitValue = isChecked;
            }

            this.$emit('input', emitValue);
        },
    },
};
</script>
