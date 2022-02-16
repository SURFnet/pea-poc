<template>
    <div
        v-if="showModal"
        class="fixed bottom-0 inset-x-0 sm:inset-0 sm:flex sm:items-center sm:justify-center | p-4 | z-40"
    >
        <transition
            v-if="showBackdrop"
            enter-active-class="ease-out duration-300"
            enter-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-class="opacity-100"
            leave-to-class="opacity-0"
            appear
            @before-leave="backdropLeaving = true"
            @after-leave="backdropLeaving = false"
        >
            <div class="fixed inset-0 transition-opacity" @click="close">
                <div class="bg-black bg-opacity-50 | absolute inset-0" />
            </div>
        </transition>

        <transition
            v-if="showContent"
            enter-active-class="ease-out duration-300"
            enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="ease-in duration-200"
            leave-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            appear
            @before-leave="cardLeaving = true"
            @after-leave="cardLeaving = false"
        >
            <div class="transform transition-all" :class="modalClasses">
                <div
                    class="relative | max-h-almost overflow-y-auto | bg-white rounded-lg shadow-xl | px-4 pt-5 pb-4 sm:p-6"
                    :class="modalStyle"
                >
                    <div>
                        <CloseButton class="absolute top-4 right-4" @close="close" />

                        <slot name="title" />
                    </div>

                    <slot />
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import CloseButton from '@/components/CloseButton';

export default {
    components: {
        CloseButton,
    },
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        size: {
            type: String,
            default: 'base',
            /**
             * Validates the right type.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['sm', 'base', 'lg'].indexOf(value) !== -1;
            },
        },
        modalStyle: {
            type: String,
            default: 'bg-white',
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            showModal: false,
            showBackdrop: false,
            showContent: false,
            backdropLeaving: false,
            cardLeaving: false,
        };
    },
    computed: {
        /**
         * Determines if we are leaving the modal.
         *
         * @returns {object}
         */
        leaving() {
            return this.backdropLeaving || this.cardLeaving;
        },

        /**
         * Determines the classes relevant for the modal.
         *
         * @returns {string}
         */
        modalClasses() {
            const classes = ['sm:w-full'];

            if (this.size === 'base') {
                classes.push('sm:max-w-xl');
            }

            if (this.size === 'lg') {
                classes.push('sm:max-w-4xl');
            }

            if (this.size === 'sm') {
                classes.push('sm:max-w-md');
            }

            return classes.join(' | ');
        },
    },
    watch: {
        value: {
            /**
             * Handles opening the modal.
             *
             * @param {boolean} newValue
             */
            handler(newValue) {
                if (newValue) {
                    this.show();
                } else {
                    this.close();
                }
            },
            immediate: true,
        },
        /**
         * Handles leaving the modal.
         *
         * @param {boolean} newValue
         */
        leaving(newValue) {
            if (newValue === false) {
                this.showModal = false;
                this.$emit('close');
                this.$emit('input', false);
            }
        },

        /**
         * Emit a closed event when all transition are done and the modal is closed.
         *
         * @param {boolean} newValue
         */
        showModal(newValue) {
            if (newValue === false) {
                this.$emit('closed');
            }
        },
    },
    /**
     * Handles the created event.
     *
     * Makes sure the modal closes if the escape key is pressed.
     */
    created() {
        const onEscape = (event) => {
            if (this.showModal && event.keyCode === 27) {
                this.close();
            }
        };

        document.addEventListener('keydown', onEscape);

        this.$once('hook:destroyed', () => {
            document.removeEventListener('keydown', onEscape);
        });
    },
    methods: {
        /**
         * Handles showing the modal.
         */
        show() {
            this.showModal = true;
            this.showBackdrop = true;
            this.showContent = true;
        },
        /**
         * Handles closing the modal.
         */
        close() {
            this.showBackdrop = false;
            this.showContent = false;
        },
    },
};
</script>
