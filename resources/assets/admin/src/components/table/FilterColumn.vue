<template>
    <th v-if="options">
        <select
            v-model="localValue"
            class="w-full block border-gray-300 | sm:text-sm | focus:ring-primary-light focus:border-primary"
        >
            <option />
            <option v-for="option in options" :key="option.value" :value="option.value" v-text="option.label" />
        </select>
    </th>
    <th v-else>
        <input
            v-model="localValue"
            class="w-full block border-gray-300 | sm:text-sm | focus:ring-primary-light focus:border-primary"
            type="text"
        />
    </th>
</template>

<script>
import { debounce } from 'lodash';

export default {
    props: {
        value: {
            type: String,
            default: null,
        },
        column: {
            type: String,
            required: true,
        },
        options: {
            type: Array,
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
            /* eslint-disable func-names */
            set: debounce(function (value) {
                this.$emit('input', value);
            }, 500),
            /* eslint-enable func-names */
        },
    },
};
</script>
