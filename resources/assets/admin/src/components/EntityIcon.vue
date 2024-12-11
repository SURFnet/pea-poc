<template>
    <div :class="iconSize">
        <img
            :src="image"
            :alt="text"
            style="object-fit: cover; height: 100%; width: 100%"
        />
    </div>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            default: null,
        },
        image: {
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
                return ['sm', 'md', 'lg'].indexOf(value) !== -1;
            },
        },
    },
    computed: {
        /**
         * Determines the size of the icon
         *
         * @returns {string}
         */
        iconSize() {
            const classes = ['flex flex-shrink-0 justify-center items-center'];

            if (this.size === 'lg') {
                classes.push('w-14 h-14');
            }

            if (this.size === 'md') {
                classes.push('w-8 h-8');
            }

            if (this.size === 'sm') {
                classes.push('w-5 h-5');
            }

            return classes.join(' | ');
        },
    },
};
</script>
