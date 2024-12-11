<template>
    <div class="border-t-2 border-gray-300">
        <div class="space-y-2 py-6">
            <h3
                class="text-xl text-black font-bold"
                v-text="trans('page.shared.tool.experiences')"
            />

            <div class="flex justify-between">
                <div class="flex items-center">
                    <p
                        v-text="
                            trans_choice('page.shared.tool.total_experiences', tool.total_experiences, {
                                count: tool.total_experiences,
                            })
                        "
                    />
                </div>

                <Btn
                    v-if="tool.permissions.share_experience"
                    type="button"
                    variant="default-dark"
                    :disabled="isImpersonating"
                    @click="modalOpen = true"
                >
                    {{ trans('page.shared.tool.share_experience') }}
                </Btn>
            </div>
        </div>

        <ShareExperienceModal
            :open="modalOpen"
            :tool="tool"
            @closed="modalOpen = false"
        />

        <div class="border-t-2 border-gray-300 | sm:divide-y sm:divide-gray-200 | bg-gray-50 | px-6 py-4">
            <template v-if="tool.total_experiences > 0">
                <slot />
            </template>

            <template v-else>
                {{ trans('page.shared.tool.no_experiences') }}
            </template>
        </div>
    </div>
</template>

<script>
import ShareExperienceModal from '@/components/modal/ShareExperienceModal';
import Btn from '@/components/Btn';

export default {
    components: {
        ShareExperienceModal,
        Btn,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            modalOpen: false,
        };
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
