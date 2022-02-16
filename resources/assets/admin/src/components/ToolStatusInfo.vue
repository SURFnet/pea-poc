<template>
    <div>
        <span :class="pillClasses" @click.prevent="modalOpen = true">
            {{ text }}

            <FontAwesomeIcon icon="info-circle" class="opacity-25 | ml-1" />
        </span>

        <StatusLegendModal :status="status" :open="modalOpen" @closed="modalOpen = false" />
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
                return (
                    ['recommended', 'supported', 'free_to_use', 'not_recommended', 'prohibited', 'unrated'].indexOf(
                        value
                    ) !== -1
                );
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
                recommended: 'recommended',
                supported: 'supported',
                free_to_use: 'free-to-use',
                not_recommended: 'not-recommended',
                prohibited: 'prohibited',
                unrated: 'unrated',
            };

            const pillClasses = [genericBase];

            pillClasses.push(variants[this.status]);

            return pillClasses.join(' | ');
        },
    },
};
</script>

<style scoped>
.recommended {
    background-color: #b5f2c6;
}

.supported {
    background-color: #b3e5ff;
}

.free-to-use {
    background-color: #ffeca7;
}

.not-recommended {
    background-color: #ffb75c;
}

.prohibited {
    background-color: #ffc5c1;
}

.unrated {
    background-color: #dadada;
}

.unpublished {
    background-color: #ffffff;
    border: 1px solid #dadada;
}
</style>
