<template>
    <inertia-link
        :href="route('other.tool.index')"
        class="w-full md:w-2/3 flex flex-row | bottom-heavy-border | text-black rounded-lg cursor-pointer hover:no-underline hover:border-gray-300 | text-left | transition-all | px-2 py-4 space-x-4 | group"
    >
        <div>
            <div class="flex flex-row items-center space-x-2">
                <div class="w-6 h-6 | flex justify-center items-center rounded-full bg-yellow-500">
                    <FontAwesomeIcon icon="bullhorn" class="text-white text-sm" />
                </div>

                <p class="text-sm uppercase" v-text="trans('page.our.tool.tip.tip')" />
            </div>
        </div>
        <div class="space-y-4">
            <p class="text-black font-bold" v-text="trans('page.our.tool.tip.title')" />
            <p v-text="trans('page.our.tool.tip.text')" />
            <Btn variant="default-dark" v-text="trans('page.our.tool.tip.button')" />
        </div>
    </inertia-link>
</template>

<script>
import Btn from '@/components/Btn';

export default {
    components: {
        Btn,
    },
    methods: {
        /**
         * Handles the click action on a tool card.
         */
        onClick() {
            if (!this.tool.permissions.view) {
                return;
            }

            this.$inertia.visit(this.url);
        },
    },
};
</script>
