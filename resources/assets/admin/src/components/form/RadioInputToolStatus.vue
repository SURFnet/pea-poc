<template>
    <div>
        <InputLabel
            v-if="label"
            :required="required"
            :tool-tip="toolTip"
            :large-label="largeLabel"
        >
            {{ label }}
        </InputLabel>

        <div class="flex flex-row flex-wrap | mt-4">
            <div
                v-for="item in options"
                :key="item.value"
                class="flex items-center | space-x-2"
            >
                <div
                    class="flex flex-row items-center | cursor-pointer"
                    @click="handleSelect(item.value)"
                >
                    <input
                        :id="`radio-input-${item.value}`"
                        ref="input"
                        v-model="localValue"
                        :name="radioGroupName"
                        :value="item.value"
                        type="radio"
                        v-bind="$attrs"
                        class="h-4 w-4 | text-blue-600 border-gray-300 | focus:ring-blue-400 | mr-1"
                        :class="inputClass"
                        :checked="isChecked(item)"
                    />

                    <div class="py-1 mr-2">
                        <ToolStatus
                            class="flex-shrink-0"
                            :for="`radio-input-${item.value}`"
                            :status="item.value"
                            :text="item.label"
                        />
                    </div>
                </div>
            </div>
        </div>

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
import ToolStatus from '@/components/ToolStatus';

export default {
    components: {
        InputLabel,
        InvalidFeedback,
        HelpText,
        ToolStatus,
    },
    inheritAttrs: false,
    props: {
        value: {
            type: [Number, String, Boolean, Array],
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        toolTip: {
            type: String,
            default: null,
        },
        options: {
            type: Array,
            required: true,
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
    /** @returns {object} */
    data() {
        return {
            localValue: this.value,
            radioGroupName: `radio-group-${uniqueId()}`,
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
    methods: {
        /**
         * @param {object} item
         * @param {string} item.value
         *
         * @returns {boolean}
         */
        isChecked({ value }) {
            return this.value === value;
        },
        /**
         * Handle the checking of an option.
         *
         * @param {string} value
         */
        handleSelect(value) {
            this.localValue = value;
            this.$emit('input', this.localValue);
        },
    },
};
</script>
