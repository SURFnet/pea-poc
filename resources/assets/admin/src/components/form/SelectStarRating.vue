<template>
    <div>
        <div class="flex items-center | space-x-2">
            <div class="flex items-center">
                <StarIcon
                    v-for="iteration in 5"
                    :key="iteration"
                    class="cursor-pointer"
                    :active="ratingClass(rating, iteration)"
                    @click.native="confirmRating(iteration)"
                    @mouseover.native="setRating(iteration)"
                    @mouseleave.native="resetRating()"
                />
            </div>

            <p v-if="label" v-text="label" />
        </div>

        <InvalidFeedback v-if="error" :error="error" class="mt-2" />
    </div>
</template>

<script>
import StarIcon from '@/components/StarIcon';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback';

export default {
    components: {
        StarIcon,
        InvalidFeedback,
    },
    props: {
        value: {
            type: Number,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        error: {
            type: String,
            default: null,
        },
    },
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            confirmed: false,
        };
    },
    computed: {
        rating: {
            /**
             * Retrieves the value.
             *
             * @returns {number}
             */
            get() {
                return Number(this.value || 0);
            },
            /**
             * Handles any value updates.
             *
             * @param {number} value
             */
            set(value) {
                this.$emit('input', value);
            },
        },
    },
    methods: {
        /**
         * Defines the color of the individual star SVG depending on the rating
         *
         * @param {number} rating
         * @param {number} iteration
         *
         * @returns {boolean}
         */
        ratingClass(rating, iteration) {
            return rating >= iteration;
        },
        /**
         * Confirms the selection
         *
         * @param {number} iteration
         */
        confirmRating(iteration) {
            this.confirmed = true;
            this.rating = iteration;
        },
        /**
         * Updates the currently selected rating
         *
         * @param {number} iteration
         */
        setRating(iteration) {
            if (!this.confirmed) {
                this.rating = iteration;
            }
        },
        /**
         * Resets the rating
         */
        resetRating() {
            if (!this.confirmed) {
                this.rating = 0;
            }
        },
    },
};
</script>
