<template>
    <inertia-link
        :href="route('our.tool.index', { 'filter[categories][]': category.id })"
        class="flex flex-col justify-between | bg-white border-t border-l-2 border-r-2 border-b-4 border-gray-200 rounded-lg cursor-pointer hover:no-underline hover:border-blue-500 | text-left | transition-all | px-2 py-4 | group"
    >
        <div>
            <p class="font-bold text-black group-hover:text-blue-500 | transition-all | mb-4" v-text="category.name" />
            <p class="text-black text-sm font-light | mb-2" v-text="category.description" />
        </div>
        <div class="flex flex-row-reverse">
            <ArrowIcon class="text-black text-xl | group-hover:text-blue-500 | transition-all" />
        </div>
    </inertia-link>
</template>

<script>
import ArrowIcon from '@/components/ArrowIcon';

export default {
    components: {
        ArrowIcon,
    },
    props: {
        category: {
            type: Object,
            required: true,
        },
    },
};
</script>
