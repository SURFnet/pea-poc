<template>
    <div class="bg-gray-100 | pt-20 pb-10">
        <div class="container | grid lg:grid-cols-12">
            <div class="w-full | flex flex-row | lg:grid lg:grid-cols-3 lg:col-span-7 | space-x-10">
                <div class="flex flex-row flex-shrink-0 | lg:col-span-1 | space-x-10">
                    <InertiaLink :href="backUrl">
                        <FontAwesomeIcon
                            class="text-black text-2xl | hover:text-blue-500"
                            icon="arrow-left"
                        />
                    </InertiaLink>

                    <img
                        :src="tool.logo_url"
                        :alt="tool.name"
                        class="h-40 w-40"
                    />
                </div>

                <div class="w-full flex flex-col | flex-shrink-1 | lg:col-span-2 | space-y-4">
                    <div class="flex justify-between items-center | mb-1">
                        <h2
                            class="text-4xl leading-10 text-black | sm:text-3xl sm:leading-10 sm:truncate"
                            v-text="tool.name"
                        />

                        <FollowToolButton
                            v-if="!editing"
                            :following="following"
                            :tool="tool"
                        />
                    </div>

                    <p v-text="tool.description_short_stripped_tags" />

                    <div class="flex justify-between">
                        <div>
                            <p
                                class="font-bold text-sm | mb-2"
                                v-text="
                                    trans('page.our.tool.show.header.experiences', {
                                        institute: $page.props.currentUser.institute.short_name,
                                    })
                                "
                            />

                            <div class="flex">
                                <p v-text="experiences" />
                            </div>
                        </div>

                        <div>
                            <div class="font-bold text-sm | mb-2">
                                <span
                                    v-text="
                                        trans('page.our.tool.show.header.for_institute', {
                                            institute: $page.props.currentUser.institute.short_name,
                                        })
                                    "
                                />

                                <ToolTip
                                    v-if="tool.institute.tooltips.status"
                                    class="font-medium"
                                    :text="tool.institute.tooltips.status"
                                />
                            </div>

                            <ToolStatusInfo
                                :status="tool.institute.status"
                                :text="tool.institute.status_display"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ToolStatusInfo from '@/components/ToolStatusInfo';
import ToolTip from '@/components/ToolTip.vue';
import FollowToolButton from '@/components/FollowToolButton.vue';

export default {
    components: {
        FollowToolButton,
        ToolTip,
        ToolStatusInfo,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
        following: {
            type: Boolean,
            default: false,
        },
        backUrl: {
            type: String,
            required: true,
        },
        editing: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Determines which text needs to be shown
         * @returns {string}
         */
        experiences() {
            if (this.tool.total_experiences === 0) {
                return trans('page.shared.tool.no_experiences');
            }

            return trans_choice('page.shared.tool.total_experiences', this.tool.total_experiences, {
                count: this.tool.total_experiences,
            });
        },
    },
};
</script>
