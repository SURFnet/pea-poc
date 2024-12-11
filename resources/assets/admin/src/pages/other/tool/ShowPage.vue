<template>
    <div class="bg-white">
        <ToolHeader
            :tool="tool"
            :following="following"
            :back-url="backUrl"
            :show-info-box="tool.permissions.add_to_collection"
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
                    <div>
                        <h3
                            class="text-xl text-black | mb-3"
                            v-text="trans('page.other.tool.show.headings.features')"
                        />

                        <div
                            v-if="tool.features.length"
                            class="flex flex-wrap items-center gap-x-4 gap-y-2"
                        >
                            <TagPill
                                v-for="feature in tool.features"
                                :key="feature.id"
                                :href="getFilterUrlByTag(feature)"
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';
import InstituteBox from '@/components/InstituteBox';

import ToolHeader from '@/pages/other/tool/components/ToolHeader';
import ToolTabs from '@/pages/other/tool/components/ToolTabs';

import ProductTab from '@/pages/other/tool/components/tabs/ProductTab';
import TechnicalTab from '@/pages/other/tool/components/tabs/TechnicalTab';
import PrivacyAndSecurityTab from '@/pages/other/tool/components/tabs/PrivacyAndSecurityTab.vue';
import SupportTab from '@/pages/other/tool/components/tabs/SupportTab.vue';
import EducationTab from '@/pages/other/tool/components/tabs/EducationTab.vue';
import TagPill from '@/components/TagPill.vue';
import { getFilterUrlByTag } from '@/helpers/tool-filter-url';

export default {
    components: {
        TagPill,
        EducationTab,
        SupportTab,
        PrivacyAndSecurityTab,
        ToolHeader,
        ToolTabs,
        InstituteBox,
        ProductTab,
        TechnicalTab,
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
        institutes: {
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
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.other.tool.show.title', { name: this.tool.name }),
        };
    },
};
</script>
