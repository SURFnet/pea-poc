<template>
    <div>
        <InputLabel
            v-if="label"
            :for="id"
            :large-label="largeLabel"
            :tool-tip="toolTip"
        >
            {{ label }}
        </InputLabel>

        <!--
            The Multiselect component uses general styling from the library with some custom adjustments.
            See `_tag-input.scss`
        -->
        <Multiselect
            ref="input"
            v-model="selectedTags"
            label="name"
            open-direction="bottom"
            class="w-full"
            :options="availableTags"
            :multiple="true"
            :taggable="false"
            :track-by="keyForValue"
            :hide-selected="true"
            :placeholder="placeholder"
            :select-label="selectLabel"
            :selected-label="selectedLabel"
            @input="handleInput"
        />

        <InvalidFeedback
            v-if="error"
            :error="error"
            class="mt-2"
        />

        <HelpText
            v-if="text"
            :text="text"
            class="mt-2"
        />
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import uniqueId from 'lodash/uniqueId';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback.vue';
import HelpText from '@/components/form/shared/HelpText.vue';
import InputLabel from '@/components/form/shared/InputLabel.vue';

export default {
    components: { InputLabel, HelpText, InvalidFeedback, Multiselect },
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
                return `tag-input-${uniqueId()}`;
            },
        },
        label: {
            type: String,
            default: null,
        },
        toolTip: {
            type: String,
            default: null,
        },
        text: {
            type: String,
            default: null,
        },
        placeholder: {
            type: String,
            default: null,
        },
        selectLabel: {
            type: String,
            default: null,
        },
        selectedLabel: {
            type: String,
            default: null,
        },
        value: {
            type: Array,
            default: () => [],
        },
        availableTags: {
            type: Array,
            required: true,
        },
        keyForValue: {
            type: String,
            default: 'id',
        },
        error: {
            type: String,
            default: null,
        },
        largeLabel: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['input'],
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            selectedTags: this.getSelectedTags(this.value),
        };
    },
    watch: {
        /**
         * Watches for value changes
         *
         * @param {Array} newValue
         */
        value(newValue) {
            this.selectedTags = this.getSelectedTags(newValue);
        },
    },
    methods: {
        /**
         * Sets the selected tags by (key for) value
         *
         * @param {Array} values
         *
         * @returns {Array}
         */
        getSelectedTags(values) {
            return values.map((value) =>
                this.availableTags.find((availableTag) => availableTag[this.keyForValue] === value)
            );
        },
        /**
         * Emits an input change
         *
         * @param {object} newSelectedTags
         */
        handleInput(newSelectedTags) {
            this.$emit(
                'input',
                newSelectedTags.map((tag) => tag[this.keyForValue])
            );
        },
    },
};
</script>
