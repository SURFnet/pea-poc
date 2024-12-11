<template>
    <div
        class="fixed inset-0 flex items-end justify-center pointer-events-none sm:items-start sm:justify-end | px-4 py-6 | sm:p-6"
    >
        <transition
            enter-active-class="ease-out duration-300"
            enter-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="ease-in duration-100"
            leave-class="opacity-100"
            leave-to-class="opacity-0"
            appear
        >
            <div
                v-if="visible"
                class="max-w-sm w-full | bg-white shadow-lg rounded-lg pointer-events-auto | transform transition-all"
            >
                <div class="bg-white rounded-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <FontAwesomeIcon
                                    :icon="icon"
                                    :class="iconClasses"
                                    size="lg"
                                />
                            </div>

                            <div class="w-0 flex-1 | ml-3">
                                <div v-if="title">
                                    <h3 class="text-sm leading-5 font-medium | mb-1">
                                        {{ title }}
                                    </h3>

                                    <div class="text-sm leading-5">
                                        {{ message }}
                                    </div>
                                </div>

                                <div v-else>
                                    <h3 class="text-sm leading-5 font-medium | mb-1">
                                        {{ message }}
                                    </h3>
                                </div>
                            </div>

                            <div class="flex-shrink-0 flex | ml-4">
                                <button
                                    type="button"
                                    class="inline-flex text-gray-400 hover:text-gray-500 | focus:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    :title="trans('action.close')"
                                    @click="dismiss()"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: false,
            default: null,
        },
        message: {
            type: String,
            required: true,
        },
        level: {
            type: String,
            required: true,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            visible: true,
        };
    },
    computed: {
        /**
         * Determines the icon classes.
         *
         * @returns {string}
         */
        iconClasses() {
            let classes = '';

            if (this.level === 'info') {
                classes += 'text-blue-500 ';
            }

            if (this.level === 'success') {
                classes += 'text-green-500 ';
            }

            if (this.level === 'warning') {
                classes += 'text-yellow-500 ';
            }

            if (this.level === 'danger') {
                classes += 'text-red-500 ';
            }

            return classes;
        },
        /**
         * Determines the icon.
         *
         * @returns {string}
         */
        icon() {
            if (this.level === 'info') {
                return 'exclamation-circle';
            }

            if (this.level === 'success') {
                return 'check-circle';
            }

            // danger
            return 'times-circle';
        },
    },
    watch: {
        '$page.props.flashNotifications': {
            /**
             * Handles the change.
             */
            handler() {
                this.visible = true;
            },
            deep: true,
        },
    },
    methods: {
        /**
         * Dismisses the notification.
         */
        dismiss() {
            this.visible = false;
        },
    },
};
</script>
