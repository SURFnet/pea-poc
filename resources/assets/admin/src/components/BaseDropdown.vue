<template>
    <div
        ref="dropdown"
        class="dropdown"
    >
        <slot
            name="button"
            :is-open="open"
            :toggle="toggle"
        >
            <Btn
                class="flex items-center font-bold"
                :variant="variant"
                :class="{ 'is-active': open }"
                @click="toggle"
            >
                {{ buttonText }}
            </Btn>
        </slot>

        <transition
            enter-active-class="ease-out duration-200"
            enter-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="ease-in duration-75"
            leave-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                class="transform transition-all | z-10"
                :class="[
                    positionClass,
                    {
                        'dropdown-menu': absolute === true,
                        '-left-32': pushLeft,
                    },
                ]"
            >
                <div :class="{ 'dropdown-menu': absolute === false }">
                    <slot />
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import Btn from '@/components/Btn';

export default {
    components: {
        Btn,
    },
    props: {
        buttonText: {
            type: String,
            default: '...',
        },
        position: {
            type: String,
            default: 'left',
            /**
             * Validates the right type.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['left', 'right'].indexOf(value) !== -1;
            },
        },
        variant: {
            type: String,
            default: 'default',
        },
        absolute: {
            type: Boolean,
            default: false,
        },
        pushLeft: {
            type: Boolean,
            default: false,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            open: false,
        };
    },
    computed: {
        /**
         * Classes used for positioning and animating the dropdownmenu.
         *
         * @returns {string}
         */
        positionClass() {
            const classList = [];

            if (this.position === 'right') {
                classList.push('right-0 origin-top-right');
            }

            if (this.position === 'left') {
                classList.push('left-0 origin-top-left');
            }

            if (this.absolute === false) {
                classList.push('relative');
            }

            return classList.join(' | ');
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
         * Toggle the dropdown menu.
         *
         * @param {object} event
         */
        toggle(event) {
            event.preventDefault();

            this.open = !this.open;
        },
        /**
         * Close the dropdown menu.
         */
        close() {
            this.open = false;
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
                this.close();
            }
        },
    },
};
</script>
