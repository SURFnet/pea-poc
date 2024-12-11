<template>
    <div
        class="w-96 | flex flex-row | bottom-heavy-border | text-black rounded-lg hover:no-underline hover:border-gray-300 | text-left | transition-all | px-2 py-4 space-x-4 | group"
    >
        <div>
            <FontAwesomeIcon
                icon="exclamation-circle"
                class="text-black | ml-2"
            />
        </div>

        <div class="space-y-4">
            <p v-text="trans('page.other.tool.show.header.info.text')" />

            <div class="w-full | space-x-2">
                <Btn
                    inertia
                    :href="route('information-manager.tool.create', tool)"
                    :disabled="isImpersonating"
                    variant="primary"
                >
                    {{ trans('page.other.tool.show.header.info.add') }}
                </Btn>

                <Btn
                    inertia
                    :href="route('information-manager.tool.create', { tool: tool, status: 'disallowed' })"
                    :disabled="isImpersonating"
                    class="font-source-sans"
                    variant="secondary"
                >
                    {{ trans('page.other.tool.show.header.info.prohibit') }}
                </Btn>
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
    computed: {
        /**
         * Determines if the current user is impersonating another institute
         *
         * @returns {boolean}
         */
        isImpersonating() {
            return this.$page.props.isImpersonating === true;
        },
    },
};
</script>
