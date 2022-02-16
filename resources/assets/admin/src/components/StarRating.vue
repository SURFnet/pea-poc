<template>
    <div class="flex items-center">
        <StarIcon v-for="iteration in 5" :key="iteration" :active="activeClass(rating, iteration)" />
    </div>
</template>

<script>
import StarIcon from '@/components/StarIcon';

export default {
    components: {
        StarIcon,
    },
    props: {
        rating: {
            type: Number,
            default: 0,
        },
    },
    methods: {
        /**
         * Defines the color of the individual star SVG depending on the rating
         *
         * @param {number} rating
         * @param {number} iteration
         *
         * @returns {string}
         */
        activeClass(rating, iteration) {
            return rating >= iteration;
        },
    },
};
</script>
