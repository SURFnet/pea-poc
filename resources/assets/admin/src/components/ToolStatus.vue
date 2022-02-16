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
                return (
                    [
                        'recommended',
                        'supported',
                        'free_to_use',
                        'not_recommended',
                        'prohibited',
                        'unrated',
                        'unpublished',
                    ].indexOf(value) !== -1
                );
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
                recommended: 'recommended',
                supported: 'supported',
                free_to_use: 'free-to-use',
                not_recommended: 'not-recommended',
                prohibited: 'prohibited',
                unrated: 'unrated',
                unpublished: 'unpublished',
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
