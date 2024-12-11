<template>
    <div :class="FormGroupBackground">
        <h3
            class="text-xl font-semibold leading-6 text-gray-900 | mb-4"
            v-text="title"
        />

        <div class="md:grid md:grid-cols-2 md:gap-20 | mb-10">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: null,
        },
        grayBackground: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Sets the baground to gray according to the prop 'grayBackground'
         *
         * @returns {string}
         */
        FormGroupBackground() {
            if (this.grayBackground) {
                return 'bg-gray-50 | -mx-5 md:-mx-14 p-6 mb-6 md:px-14';
            }

            return '';
        },
    },
};
</script>
