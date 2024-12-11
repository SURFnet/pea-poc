<template>
    <div>
        <span
            :class="pillClasses"
            @click.prevent="modalOpen = true"
        >
            {{ text }}

            <FontAwesomeIcon
                icon="info-circle"
                class="opacity-25 | ml-1"
            />
        </span>

        <StatusLegendModal
            :status="status"
            :open="modalOpen"
            @closed="modalOpen = false"
        />
    </div>
</template>

<script>
import StatusLegendModal from '@/components/modal/StatusLegendModal';

export default {
    components: {
        StatusLegendModal,
    },
    props: {
        status: {
            type: String,
            default: 'unrated',

            /**
             * Validates the right status.
             *
             * @param {string} value
             *
             * @returns {boolean}
             */
            validator(value) {
                return ['allowed', 'disallowed', 'allowed_under_conditions', 'unrated'].indexOf(value) !== -1;
            },
        },
        text: {
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
            modalOpen: false,
        };
    },
    computed: {
        /**
         * Determines the styling of the component.
         *
         * @returns {string}
         */
        pillClasses() {
            const genericBase = `
                rounded-full font-normal | text-sm text-black | px-3 py-1 | cursor-pointer hover:opacity-80
            `.trim();

            const variants = {
                unrated: 'unrated',
                unpublished: 'unpublished',
                allowed: 'allowed',
                allowed_under_conditions: 'allowed-under-conditions',
                disallowed: 'disallowed',
            };

            const pillClasses = [genericBase];

            pillClasses.push(variants[this.status]);

            return pillClasses.join(' | ');
        },
    },
};
</script>

<style scoped>
.allowed {
    background-color: #b5f2c6;
}

.allowed-under-conditions {
    background-color: #ffeca7;
}

.disallowed {
    background-color: #fca5a5;
}

.unrated {
    background-color: #dadada;
}

.unpublished {
    background-color: #ffffff;
    border: 1px solid #dadada;
}
</style>
