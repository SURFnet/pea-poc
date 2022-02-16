<template>
    <div class="bg-white">
        <ToolHeader :tool="tool" :back-url="backUrl" />

        <ToolTabs
            :active-tab="activeTab"
            :tool-status="tool.institute.status"
            :with-technical-info="tool.permissions.see_technical_information"
            @tab-selected="switchTab"
        />

        <div class="container | pt-4 | pb-16 sm:pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-8">
                <div class="lg:col-span-5">
                    <ProductDescription v-if="activeTab === 'description'" :tool="tool" :experiences="experiences" />
                    <HowToUse v-if="activeTab === 'how_to_use'" :tool="tool" />
                    <WhyNotUse v-if="activeTab === 'why_not_use'" :tool="tool.institute" />
                    <TechnicalInformation v-if="activeTab === 'technical_info'" :tool="tool" />
                </div>
                <div class="hidden lg:flex lg:col-span-1" />
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <h3
                            class="text-xl text-black"
                            v-text="
                                trans('page.our.tool.show.headings.categories', {
                                    institute: $page.props.currentUser.institute.short_name,
                                })
                            "
                        />

                        <div class="space-y-3">
                            <TagPill v-for="category in tool.institute.categories" :key="category.id" class="mr-4">
                                {{ category.name }}
                            </TagPill>
                        </div>
                    </div>

                    <PageDivider />

                    <div class="space-y-6">
                        <h3 class="text-xl text-black" v-text="trans('page.our.tool.show.headings.features')" />

                        <div class="space-y-3">
                            <TagPill v-for="feature in tool.features" :key="feature.id" class="mr-4">
                                {{ feature.name }}
                            </TagPill>
                        </div>
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

import ProductDescription from '@/pages/our/tool/components/tabs/ProductDescription';
import HowToUse from '@/pages/our/tool/components/tabs/HowToUse';
import WhyNotUse from '@/pages/our/tool/components/tabs/WhyNotUse';
import TechnicalInformation from '@/pages/our/tool/components/tabs/TechnicalInformation';
import SupportBox from '@/pages/our/tool/components/SupportBox';

export default {
    components: {
        ToolHeader,
        ToolTabs,
        TagPill,
        PageDivider,
        ProductDescription,
        HowToUse,
        WhyNotUse,
        TechnicalInformation,
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
        backUrl: {
            type: String,
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
            activeTab: 'description',
        };
    },
    methods: {
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
