<template>
    <div>
        <InputLabel
            v-if="label"
            :for="id"
            :required="required"
            :tool-tip="toolTip"
            large-label
            class="pb-2"
        >
            {{ label }}
        </InputLabel>

        <Wysiwyg
            :id="id"
            ref="input"
            v-model="localValue"
            v-bind="$attrs"
            :required="required"
            :has-error="!!error"
        />

        <InvalidFeedback
            v-if="error"
            :error="error"
        />

        <HelpText :text="text" />
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback';
import HelpText from '@/components/form/shared/HelpText';
import Wysiwyg from '@/components/Wysiwyg';

export default {
    components: {
        Wysiwyg,
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
                // the `this._uid` is a Vue variable.
                // eslint-disable-next-line
                return `text-input-${this._uid}`;
            },
        },
        value: {
            type: String,
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
    },
};
</script>
