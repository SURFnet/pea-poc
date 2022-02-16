<template>
    <div class="bg-white">
        <ToolHeader :tool="tool" :back-url="backUrl" :show-info-box="tool.permissions.add_to_collection" />

        <ToolTabs
            :active-tab="activeTab"
            :with-technical-info="tool.permissions.see_technical_information"
            @tab-selected="switchTab"
        />

        <div class="container | pt-4 | pb-16 sm:pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-8">
                <div class="lg:col-span-5">
                    <ProductDescription v-if="activeTab === 'description'" :tool="tool" :experiences="experiences" />
                    <TechnicalInformation v-else :tool="tool" />
                </div>
                <div class="hidden lg:flex lg:col-span-1" />
                <div class="lg:col-span-2 | space-y-6">
                    <h3 class="text-xl text-black" v-text="trans('page.other.tool.show.headings.features')" />

                    <div class="space-y-1">
                        <TagPill v-for="feature in tool.features" :key="feature.id" class="mr-4">
                            {{ feature.name }}
                        </TagPill>
                    </div>

                    <div v-if="institutes.length">
                        <h3 class="text-xl text-black | mb-6" v-text="institutesHeading" />

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
import TagPill from '@/components/TagPill';
import InstituteBox from '@/components/InstituteBox';

import ToolHeader from '@/pages/other/tool/components/ToolHeader';
import ToolTabs from '@/pages/other/tool/components/ToolTabs';

import ProductDescription from '@/pages/other/tool/components/tabs/ProductDescription';
import TechnicalInformation from '@/pages/other/tool/components/tabs/TechnicalInformation';

export default {
    components: {
        ToolHeader,
        ToolTabs,
        TagPill,
        InstituteBox,
        ProductDescription,
        TechnicalInformation,
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
