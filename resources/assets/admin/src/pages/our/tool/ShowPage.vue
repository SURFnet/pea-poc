<template>
    <div class="bg-white">
        <ToolHeader
            :tool="tool"
            :following="following"
            :back-url="backUrl"
        />

        <ToolTabs
            :active-tab="activeTab"
            @tab-selected="switchTab"
        />

        <div class="container | pt-4 | pb-16 sm:pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-8">
                <div class="lg:col-span-5">
                    <ProductTab
                        v-if="activeTab === 'product'"
                        :tool="tool"
                        :experiences="experiences"
                    />

                    <TechnicalTab
                        v-else-if="activeTab === 'technical'"
                        :tool="tool"
                    />

                    <PrivacyAndSecurityTab
                        v-else-if="activeTab === 'privacy_and_security'"
                        :tool="tool"
                    />

                    <SupportTab
                        v-else-if="activeTab === 'support'"
                        :tool="tool"
                    />

                    <EducationTab
                        v-else-if="activeTab === 'education'"
                        :tool="tool"
                    />
                </div>

                <div class="hidden lg:flex lg:col-span-1" />

                <div class="lg:col-span-2 | space-y-6">
                    <div
                        v-if="tool.institute.categories.length"
                        class="pb-4"
                    >
                        <h3 class="text-xl text-black mb-2">
                            <span
                                v-text="
                                    trans('page.our.tool.show.headings.categories', {
                                        institute: $page.props.currentUser.institute.short_name,
                                    })
                                "
                            />

                            <ToolTip
                                v-if="tool.institute.tooltips.categories"
                                :text="tool.institute.tooltips.categories"
                            />
                        </h3>

                        <div>
                            <TagPill
                                v-for="category in tool.institute.categories"
                                :key="category.id"
                                :href="getFilterUrlByTag(category)"
                                class="mr-4"
                            >
                                {{ category.name }}
                            </TagPill>
                        </div>
                    </div>

                    <div v-if="tool.institute.alternative_tools?.length">
                        <h3
                            class="text-xl text-black | mb-3"
                            v-text="trans('page.our.tool.show.headings.alternative_tools')"
                        />

                        <SidebarToolCard
                            v-for="alternativeTool in tool.institute.alternative_tools"
                            :key="alternativeTool.id"
                            :url="route('our.tool.show', alternativeTool.id)"
                            :tool="alternativeTool"
                        />
                    </div>

                    <div
                        v-if="tool.features.length"
                        :class="{ 'pt-4': tool.institute.categories.length }"
                    >
                        <h3
                            class="text-xl text-black | mb-3"
                            v-text="trans('page.our.tool.show.headings.features')"
                        />

                        <div class="space-y-3">
                            <TagPill
                                v-for="feature in tool.features"
                                :key="feature.id"
                                :href="getFilterUrlByTag(feature)"
                                class="mr-4"
                            >
                                {{ feature.name }}
                            </TagPill>
                        </div>
                    </div>

                    <div v-if="institutes.length">
                        <h3
                            class="text-xl text-black | mb-3"
                            v-text="institutesHeading"
                        />

                        <InstituteBox
                            v-for="institute in institutes"
                            :key="institute.id"
                            :name="institute.full_name"
                            :image="institute.logo_square_url"
                        />
                    </div>

                    <div v-if="showSupportBox()">
                        <PageDivider />

                        <SupportBox :tool="tool" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';
import TagPill from '@/components/TagPill';
import PageDivider from '@/components/page/PageDivider';

import ToolHeader from '@/pages/our/tool/components/ToolHeader';
import ToolTabs from '@/pages/our/tool/components/ToolTabs';

import SupportBox from '@/pages/our/tool/components/SupportBox';
import ProductTab from '@/pages/our/tool/components/tabs/ProductTab.vue';
import TechnicalTab from '@/pages/our/tool/components/tabs/TechnicalTab.vue';
import PrivacyAndSecurityTab from '@/pages/our/tool/components/tabs/PrivacyAndSecurityTab.vue';
import SupportTab from '@/pages/our/tool/components/tabs/SupportTab.vue';
import EducationTab from '@/pages/our/tool/components/tabs/EducationTab.vue';
import { getFilterUrlByTag } from '@/helpers/tool-filter-url';
import ToolTip from '@/components/ToolTip.vue';
import InstituteBox from '@/components/InstituteBox.vue';
import SidebarToolCard from '@/components/SidebarToolCard.vue';

export default {
    components: {
        SidebarToolCard,
        InstituteBox,
        ToolTip,
        EducationTab,
        SupportTab,
        PrivacyAndSecurityTab,
        TechnicalTab,
        ProductTab,
        ToolHeader,
        ToolTabs,
        TagPill,
        PageDivider,
        SupportBox,
    },
    layout: Layout,
    props: {
        tool: {
            type: Object,
            required: true,
        },
        experiences: {
            type: Array,
            required: true,
        },
        following: {
            type: Boolean,
            required: true,
        },
        backUrl: {
            type: String,
            required: true,
        },
        institutes: {
            type: Array,
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
            activeTab: 'product',
        };
    },
    computed: {
        /**
         * @returns {string}
         */
        institutesHeading() {
            return trans_choice('page.other.tool.show.headings.institutes', this.institutes.length, {
                count: this.institutes.length,
            });
        },
    },
    methods: {
        getFilterUrlByTag,
        /**
         * @param {string} selectedTab
         */
        switchTab(selectedTab) {
            this.activeTab = selectedTab;
        },
        /**
         *
         * Returns whether a support box with contact information should be shown to the user.
         *
         * @returns {boolean}
         */
        showSupportBox() {
            if (!this.$page.props.permissions['get-support']) {
                return false;
            }

            return this.tool.institute.support_email_1 || this.tool.institute.support_email_2;
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.our.tool.show.title', { name: this.tool.name }),
        };
    },
};
</script>
