<template>
    <inertia-link
        :href="url"
        class="relative | w-full md:w-2/3 flex flex-row | bottom-heavy-border | text-black rounded-lg cursor-pointer hover:no-underline hover:border-blue-500 | text-left | transition-all | px-2 py-4 space-x-4 | group"
    >
        <img :src="tool.image_url" :alt="tool.name" class="h-28 w-28 | border-2 border-gray-200 p-0.5" />

        <div>
            <p class="font-semibold group-hover:text-blue-500 | mb-1" v-text="tool.name" />

            <StarRating :rating="tool.rating" class="mb-2" />

            <p v-snip="2" class="font-light" v-text="tool.description_short" />
        </div>
        <div v-if="tool.institute && tool.institute.status" class="absolute top-4 right-4">
            <ToolStatusInfo :status="tool.institute.status" :text="tool.institute.status_display" />
        </div>
    </inertia-link>
</template>

<script>
import Vue from 'vue';
import VueSnip from 'vue-snip';
import StarRating from '@/components/StarRating';
import ToolStatusInfo from '@/components/ToolStatusInfo';

Vue.use(VueSnip);

export default {
    components: {
        StarRating,
        ToolStatusInfo,
    },
    props: {
        url: {
            type: String,
            required: true,
        },
        tool: {
            type: Object,
            default: null,
        },
    },
};
</script>
