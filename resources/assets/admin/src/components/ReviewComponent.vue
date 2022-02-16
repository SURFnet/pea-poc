<template>
    <div class="flex text-sm text-gray-500 | py-4">
        <div class="flex-1 | space-y-2">
            <h3 v-if="experience.title" class="text-black font-bold text-lg" v-text="experience.title" />

            <div class="flex | text-sm | space-x-2">
                <span
                    class="font-medium text-gray-900"
                    v-text="`${experience.user.name} - ${experience.user.institute.full_name}`"
                />

                <span v-text="`|`" />

                <time :datetime="experience.created_at" v-text="readableDate(experience.created_at)" />
            </div>

            <div class="flex items-center justify-between">
                <StarRating :rating="experience.rating" />

                <div class="flex | space-x-2">
                    <a v-if="experience.permissions.update" href="#" @click.prevent="modalOpen = true">
                        <FontAwesomeIcon icon="pencil-alt" class="text-gray-500 hover:text-gray-700" />
                    </a>

                    <a v-if="experience.permissions.delete" href="#" @click.prevent="deleteExperience()">
                        <FontAwesomeIcon icon="trash-alt" class="text-gray-500 hover:text-gray-700" />
                    </a>
                </div>
            </div>

            <div v-if="experience.message" class="max-w-none | prose prose-md text-gray-500">
                <!-- eslint-disable-next-line vue/no-v-html -->
                <p v-html="experience.message_display" />
            </div>
        </div>

        <EditExperienceModal
            v-if="experience.permissions.update"
            :experience="experience"
            :open="modalOpen"
            @closed="modalOpen = false"
        />
    </div>
</template>

<script>
import StarRating from '@/components/StarRating';
import EditExperienceModal from '@/components/modal/EditExperienceModal';

import { readableDate } from '@/helpers/datetime';

export default {
    components: {
        StarRating,
        EditExperienceModal,
    },
    props: {
        experience: {
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
    methods: {
        readableDate,

        /**
         * Handles the deletion of the experience.
         */
        deleteExperience() {
            // eslint-disable-next-line
            if (!window.confirm(trans('confirm.delete-experience'))) {
                return;
            }

            this.$inertia.delete(route('teacher.experience.destroy', this.experience), {
                preserveScroll: true,
            });
        },
    },
};
</script>
