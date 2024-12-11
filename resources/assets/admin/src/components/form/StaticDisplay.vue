<template>
    <div>
        <InputLabel v-if="label">
            {{ label }}
        </InputLabel>

        <div
            v-bind="$attrs"
            class="w-full block | sm:text-sm | mt-1"
            :class="inputClass"
            v-text="value"
        />

        <HelpText
            :text="text"
            class="mt-2"
        />
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel';
import HelpText from '@/components/form/shared/HelpText';

export default {
    components: {
        InputLabel,
        HelpText,
    },
    inheritAttrs: false,
    props: {
        value: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        text: {
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
    },
    computed: {
        /**
         * Determines the input class based on the current state.
         *
         * @returns {string}
         */
        inputClass() {
            const inputClasses = [];

            if (this.size === 'lg') {
                inputClasses.push('py-5 px-4');
            }

            return inputClasses.join(' | ');
        },
    },
};
</script>
