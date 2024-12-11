<template>
    <InertiaLink
        :href="getFilterUrlByTag(category)"
        class="flex flex-col justify-between | bg-white border border-gray-200 shadow-lg rounded-md cursor-pointer hover:no-underline hover:border-blue-500 | text-left | transition-all | p-6 | group"
    >
        <div>
            <p
                class="font-bold text-black group-hover:text-blue-500 | transition-all | mb-4"
                v-text="category.name"
            />

            <!-- eslint-disable vue/no-v-html -->
            <p
                class="text-black text-sm font-light | mb-2"
                v-html="category.description"
            />
            <!-- eslint-enable vue/no-v-html -->
        </div>

        <div class="flex flex-row-reverse">
            <ArrowIcon class="text-black text-xl | group-hover:text-blue-500 | transition-all" />
        </div>
    </InertiaLink>
</template>

<script>
import ArrowIcon from '@/components/ArrowIcon';
import { getFilterUrlByTag } from '@/helpers/tool-filter-url';

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
    methods: { getFilterUrlByTag },
};
</script>
