<template>
    <div class="relative">
        <button
            ref="dropdown"
            class="inline-flex justify-center items-center space-x-2 | rounded-md | px-3 py-2"
            :class="dropdownClass"
            @click="toggleMenu()"
        >
            <span
                v-if="!chevronRight"
                class="inline-block ml-2"
            >
                <FontAwesomeIcon
                    :icon="toggleChevron"
                    class="text-sm"
                    :class="chevronClass"
                />
            </span>

            <slot name="menu-header" />

            <span
                v-if="chevronRight"
                class="inline-block ml-2"
            >
                <FontAwesomeIcon
                    :icon="toggleChevron"
                    class="text-sm"
                    :class="chevronClass"
                />
            </span>
        </button>

        <transition
            enter-active-class="ease-out duration-200"
            enter-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="ease-in duration-75"
            leave-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="openMenu"
                class="dropdown-menu | transform transition-all | z-10"
                role="menu"
                aria-orientation="vertical"
            >
                <slot />
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        dark: {
            type: Boolean,
            default: false,
        },
        chevronPosition: {
            type: String,
            default: 'right',
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            openMenu: false,
        };
    },
    computed: {
        /**
         * Determines the icon for the dropdown toggle.
         *
         * @returns {string}
         */
        toggleChevron() {
            if (this.openMenu) {
                return 'chevron-up';
            }

            return 'chevron-down';
        },
        /**
         * Determines the position of the chevron icon.
         *
         * @returns {boolean}
         */
        chevronRight() {
            return this.chevronPosition === 'right';
        },
        /**
         * Determines the classes for the dropdown.
         *
         * @returns {string}
         */
        dropdownClass() {
            if (this.dark) {
                return 'font-medium text-white | hover:no-underline hover:bg-gray-700 hover:text-blue-400 | focus:bg-gray-700 focus:text-blue-400 focus:outline-none';
            }

            return 'font-base text-black | hover:no-underline hover:text-gray-500 | focus:outline-none';
        },
        /**
         * Determines the classes for the chevron icon.
         *
         * @returns {string}
         */
        chevronClass() {
            if (!this.dark) {
                return 'text-gray-500';
            }

            return '';
        },
    },
    /**
     * Runs code after an instance is mounted.
     */
    mounted() {
        document.addEventListener('click', this.onClick);
    },
    /**
     * Runs code after an instance is destroyed.
     */
    destroyed() {
        document.removeEventListener('click', this.onClick);
    },
    methods: {
        /**
         * Toggle the main menu.
         */
        toggleMenu() {
            this.openMenu = !this.openMenu;
        },
        /**
         * Event for closing the dropdown when clicking outside or on a dropdown item like a link or button.
         *
         * @param {object} event
         */
        onClick(event) {
            const onDropdownItem = event.target.classList.contains('js-dropdown-item');
            const onOutsideSelf = !this.$refs.dropdown.contains(event.target);

            if (onDropdownItem || onOutsideSelf) {
                this.openMenu = false;
            }
        },
    },
};
</script>
