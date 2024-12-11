<template>
    <div>
        <VueGallery
            :id="`gallery-${uniqueId}`"
            :images="[imageUrl]"
            :index="index"
            :options="options"
            @close="setIndex(null)"
        />

        <div
            :style="{ backgroundImage: `url(${imageUrl})`, width: '300px', height: '200px' }"
            class="m-1.5 rounded-md shadow-lg bg-center bg-no-repeat bg-cover"
            @click="setIndex(0)"
        />
    </div>
</template>

<script>
import VueGallery from 'vue-gallery';

export default {
    components: {
        VueGallery,
    },
    props: {
        uniqueId: {
            type: String,
            required: true,
        },
        imageUrl: {
            type: String,
            required: true,
        },
    },

    /**
     * @returns {object}
     */
    data() {
        return {
            index: null,
            options: {
                closeOnSlideClick: true,
            },
        };
    },

    methods: {
        /**
         * @param {number | null} index
         */
        setIndex(index) {
            this.index = index;
        },
    },
};
</script>
