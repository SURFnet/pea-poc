<template>
    <div>
        <InputLabel
            v-if="label"
            :for="id"
            :tool-tip="toolTip"
            :required="required"
        >
            {{ label }}
        </InputLabel>

        <v-select
            :id="id"
            ref="input"
            v-model="localValue"
            :class="inputClass"
            v-bind="$attrs"
            :options="options"
            :reduce="(option) => option.value"
        >
            <template #search="{ attributes, events }">
                <input
                    class="vs__search"
                    :required="isRequired"
                    v-bind="attributes"
                    v-on="events"
                />
            </template>
        </v-select>

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

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import InputLabel from '@/components/form/shared/InputLabel';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback';
import HelpText from '@/components/form/shared/HelpText';

export default {
    components: {
        vSelect,

        InputLabel,
        InvalidFeedback,
        HelpText,
    },
    inheritAttrs: false,
    props: {
        id: {
            type: String,

            /**
             * Set a default id for the select input.
             *
             * @returns {string}
             */
            default() {
                return `select-input-${uniqueId()}`;
            },
        },
        value: {
            type: [Number, String],
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
        toolTip: {
            type: String,
            default: null,
        },
    },
    computed: {
        localValue: {
            /**
             * Retrieves the value.
             *
             * @returns {string}
             */
            get() {
                return String(this.value || '');
            },
            /**
             * Handles any value updates.
             *
             * @param {string} value
             */
            set(value) {
                this.$emit('input', value);
            },
        },
        /**
         * Determines the input class based on the current state.
         *
         * @returns {string}
         */
        inputClass() {
            return {
                'vs__dropdown--error': this.error,
            };
        },
        /**
         * Determines if the select is required and should show the HTML5 error message.
         *
         * @returns {boolean}
         */
        isRequired() {
            return this.required && !this.localValue;
        },
    },
};
</script>
