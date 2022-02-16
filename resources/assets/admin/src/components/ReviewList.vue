<template>
    <div class="border-t-2 border-gray-300">
        <div class="space-y-2 py-6">
            <h3 class="text-xl text-black font-bold" v-text="trans('page.shared.tool.experiences')" />

            <div class="flex justify-between">
                <div class="flex items-center">
                    <StarRating :rating="tool.rating" />

                    <p
                        class="ml-2"
                        v-text="trans('page.shared.tool.total_experiences', { count: tool.total_experiences })"
                    />
                </div>

                <Btn
                    v-if="tool.permissions.share_experience"
                    type="button"
                    variant="default-dark"
                    @click="modalOpen = true"
                    v-text="trans('page.shared.tool.share_experience')"
                />
            </div>
        </div>

        <ShareExperienceModal :open="modalOpen" :tool="tool" @closed="modalOpen = false" />

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
import StarRating from '@/components/StarRating';
import ShareExperienceModal from '@/components/modal/ShareExperienceModal';
import Btn from '@/components/Btn';

export default {
    components: {
        StarRating,
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
};
</script>
