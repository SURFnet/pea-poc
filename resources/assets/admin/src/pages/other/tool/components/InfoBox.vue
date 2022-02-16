<template>
    <div
        class="w-96 | flex flex-row | bottom-heavy-border | text-black rounded-lg hover:no-underline hover:border-gray-300 | text-left | transition-all | px-2 py-4 space-x-4 | group"
    >
        <div>
            <FontAwesomeIcon icon="exclamation-circle" class="text-black | ml-2" />
        </div>
        <div class="space-y-4">
            <p v-text="trans('page.other.tool.show.header.info.text')" />
            <div class="w-full | space-x-2">
                <Btn
                    inertia
                    :href="route('information-manager.tool.create', tool)"
                    variant="primary"
                    v-text="trans('page.other.tool.show.header.info.add')"
                />
                <Btn
                    inertia
                    :href="route('information-manager.tool.prohibited.create', tool)"
                    class="font-source-sans"
                    variant="secondary"
                    v-text="trans('page.other.tool.show.header.info.prohibit')"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Btn from '@/components/Btn';

export default {
    components: {
        Btn,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
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
