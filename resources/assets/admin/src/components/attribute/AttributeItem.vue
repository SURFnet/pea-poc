<template>
    <div class="sm:grid sm:grid-cols-3 sm:gap-4 | py-4 sm:py-5 sm:px-6">
        <dt class="text-sm font-source-sans text-gray-500">
            <template v-if="label">
                {{ label }}
            </template>

            <slot
                v-else
                name="label"
            />
        </dt>

        <dd class="mt-1 text-sm font-source-sans text-gray-900 font-medium | sm:mt-0 sm:col-span-2">
            <slot v-if="hasValue" />

            <span v-else>—</span>
        </dd>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: null,
        },
    },
    computed: {
        /**
         * Determine whether or not a value can be shown.
         *
         * @returns {boolean}
         */
        hasValue() {
            if (!this.$slots.default) {
                return false;
            }

            const hasText = this.$slots.default[0].text && this.$slots.default[0].text.trim();
            const hasHtml = this.$slots.default[0].tag;

            return hasText || hasHtml;
        },
    },
};
</script>
