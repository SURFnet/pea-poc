<template>
    <component :is="componentType" :href="getLocation" :class="buttonClasses" :disabled="disabled" @click="handleClick">
        <slot />
    </component>
</template>

<script>
export default {
    props: {
        variant: {
            type: String,
            default: 'default',

            /**
             * Validates the right variant.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return (
                    [
                        'default',
                        'default-dark',
                        'primary',
                        'secondary',
                        'warning',
                        'danger',
                        'danger-outline',
                        'inverse',
                        'no-outline',
                    ].indexOf(value) !== -1
                );
            },
        },
        href: {
            type: String,
            required: false,
            default: null,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        inertia: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Determines the component type.
         *
         * @returns {string}
         */
        componentType() {
            if (this.inertia && !this.disabled) {
                return 'InertiaLink';
            }

            if (this.href) {
                return 'a';
            }

            return 'button';
        },

        /**
         * Determines the location for the anchor.
         *
         * @returns {string|null}
         */
        getLocation() {
            if (this.disabled) {
                return null;
            }

            return this.href;
        },
        /**
         * Determines if we are leaving the modal.
         *
         * @returns {string}
         */
        buttonClasses() {
            const genericBase = `
                inline-flex items-center |
                rounded-md border |
                font-medium text-sm |
                px-4 py-2
                focus:outline-none
                focus:ring focus:ring-offset-3 focus:ring-blue-100
            `.trim();

            const variants = {
                default: {
                    base: 'border-gray-300 | text-gray-700',
                    hover: 'hover:border-blue-500 hover:text-gray-600 | hover:no-underline',
                },
                inverse: {
                    base: 'border-gray-300 | text-white',
                    hover: 'hover:border-gray-400 hover:text-gray-100',
                },
                primary: {
                    base: 'border-transparent | bg-blue-500 | text-white',
                    hover: 'hover:bg-blue-700 hover:text-white',
                },
                secondary: {
                    base: 'border-transparent | bg-gray-700 | text-white',
                    hover: 'hover:bg-gray-600 | hover:text-white',
                },
                warning: {
                    base: 'border-transparent | bg-yellow-500 | text-white',
                    hover: 'hover:bg-yellow-400 hover:text-white',
                },
                danger: {
                    base: 'border-transparent bg-red-600 text-white',
                    hover: 'hover:bg-red-500 hover:text-white',
                },
                'danger-outline': {
                    base: 'border-red-600 text-red-600',
                    hover: 'hover:border-red-500 hover:text-red-500',
                },
                'no-outline': {
                    base: 'border-transparent | text-gray-700',
                    hover: 'hover:text-gray-400 hover:no-underline',
                },
                'default-dark': {
                    base: 'border-gray-300 | bg-gray-100 | text-gray-700',
                    hover: 'hover:border-blue-500 hover:text-gray-600 | hover:no-underline',
                },
            };

            const btnClasses = [genericBase];

            btnClasses.push(variants[this.variant].base);

            if (this.disabled) {
                btnClasses.push('focus:outline-none | opacity-50 | cursor-not-allowed');
            }

            if (!this.disabled) {
                btnClasses.push(variants[this.variant].hover);
                btnClasses.push(variants[this.variant].focus);
            }

            return btnClasses.join(' | ');
        },
    },

    methods: {
        /**
         * Handles a click event on the component.
         *
         * @param {object} event
         */
        handleClick(event) {
            if (this.disabled) {
                return;
            }

            this.$emit('click', event);
        },
    },
};
</script>
