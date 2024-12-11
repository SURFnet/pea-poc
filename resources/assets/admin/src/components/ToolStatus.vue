<template>
    <div>
        <span :class="pillClasses">
            {{ text }}
        </span>
    </div>
</template>

<script>
export default {
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
                return ['unrated', 'unpublished', 'allowed', 'allowed_under_conditions', 'disallowed'].includes(value);
            },
        },
        text: {
            type: String,
            required: true,
        },
    },
    computed: {
        /**
         * Determines the styling of the component.
         *
         * @returns {string}
         */
        pillClasses() {
            const genericBase = `
                rounded-full font-normal | text-sm text-black | px-3 py-1
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
