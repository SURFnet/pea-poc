<template>
    <div class="flex text-sm text-gray-500 | py-4">
        <div class="flex-1 | space-y-2">
            <div class="flex items-center justify-between">
                <h3
                    v-if="experience.title"
                    class="text-black font-bold text-lg"
                    v-text="experience.title"
                />

                <div class="flex | space-x-2">
                    <a
                        v-if="experience.permissions.update"
                        href="#"
                        @click.prevent="modalOpen = true"
                    >
                        <FontAwesomeIcon
                            icon="pencil-alt"
                            class="text-gray-500 hover:text-gray-700"
                        />
                    </a>

                    <a
                        v-if="experience.permissions.delete"
                        href="#"
                        @click.prevent="deleteExperience()"
                    >
                        <FontAwesomeIcon
                            icon="trash-alt"
                            class="text-gray-500 hover:text-gray-700"
                        />
                    </a>
                </div>
            </div>

            <div class="flex | text-sm | space-x-2">
                <span
                    class="font-medium text-gray-900"
                    v-text="userDisplay"
                />

                <span v-text="`|`" />

                <time
                    :datetime="experience.created_at"
                    v-text="readableDate(experience.created_at)"
                />
            </div>

            <div
                v-if="experience.message"
                class="max-w-none | prose prose-md text-gray-500"
            >
                <h4
                    class="text-black font-bold"
                    v-text="trans('experience.attributes.message')"
                />

                <ProseParagraph :value="experience.message" />
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
import { router } from '@inertiajs/vue2';

import EditExperienceModal from '@/components/modal/EditExperienceModal';
import ProseParagraph from '@/components/ProseParagraph';

import { readableDate } from '@/helpers/datetime';

export default {
    components: {
        EditExperienceModal,
        ProseParagraph,
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
    computed: {
        /**
         * Returns the value to display for the user who created the experience
         *
         * @returns {string}
         */
        userDisplay() {
            const userName = this.experience.user
                ? this.experience.user.name
                : trans('experience.user_outside_institute');

            return `${userName} - ${this.experience.institute.full_name}`;
        },
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

            router.delete(route('teacher.experience.destroy', this.experience), {
                preserveScroll: true,
            });
        },
    },
};
</script>
