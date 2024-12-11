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
                                    trans('page.other.tool.show.header.experiences', {
                                        institute: $page.props.currentUser.institute.short_name,
                                    })
                                "
                            />

                            <div class="flex">
                                <p v-text="experiences" />
                            </div>
                        </div>

                        <div>
                            <p
                                class="font-bold text-sm | mb-2"
                                v-text="
                                    trans('page.other.tool.show.header.status', {
                                        institute: $page.props.currentUser.institute.short_name,
                                    })
                                "
                            />

                            <ToolStatusInfo
                                status="unrated"
                                :text="trans('institute.tool.statuses.unrated')"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="showInfoBox"
                class="flex flex-row items-center justify-center lg:justify-end lg:items-start lg:col-span-5 | pt-4 lg:pt-0"
            >
                <InfoBox :tool="tool" />
            </div>
        </div>
    </div>
</template>

<script>
import ToolStatusInfo from '@/components/ToolStatusInfo';
import InfoBox from '@/pages/other/tool/components/InfoBox';
import FollowToolButton from '@/components/FollowToolButton.vue';

export default {
    components: {
        FollowToolButton,
        ToolStatusInfo,
        InfoBox,
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
        showInfoBox: {
            type: Boolean,
            default: false,
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
