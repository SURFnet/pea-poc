<template>
    <Layout>
        <div class="relative | space-y-6">
            <BannerHeader
                page="our-tools"
                :title="$page.props.currentUser.institute.full_name"
                :image="$page.props.currentUser.institute.banner_url"
                :search-placeholder="trans('page.shared.section-header.search-our')"
            />

            <h3
                class="text-2xl text-black font-bold"
                v-text="trans('page.our.tool.index.heading', { count: tools.length })"
            />

            <ToolCard v-for="tool in tools" :key="tool.id" :tool="tool" :url="route('our.tool.show', tool)" />

            <TipCard />
        </div>
    </Layout>
</template>

<script>
import Layout from '@/layouts/SidebarLayout';

import BannerHeader from '@/components/page/BannerHeader';
import ToolCard from '@/components/ToolCard';
import TipCard from '@/pages/our/components/TipCard';

export default {
    components: {
        Layout,
        BannerHeader,
        ToolCard,
        TipCard,
    },
    props: {
        tools: {
            type: Array,
            default: null,
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.our.tool.index.title'),
        };
    },
};
</script>
