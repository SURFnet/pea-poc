<template>
    <Layout>
        <div class="space-y-6">
            <BannerHeader
                page="other-tools"
                :title="trans('page.other.tool.index.title')"
                :subtitle="trans('page.other.tool.index.subtitle')"
                :search-placeholder="trans('page.shared.section-header.search-other')"
            />

            <h3
                class="text-2xl text-black font-bold"
                v-text="trans('page.other.tool.index.heading', { count: tools.length })"
            />

            <ToolCard v-for="tool in tools" :key="tool.id" :tool="tool" :url="route('other.tool.show', tool)" />
        </div>
    </Layout>
</template>

<script>
import Layout from '@/layouts/SidebarLayout';

import BannerHeader from '@/components/page/BannerHeader';
import ToolCard from '@/components/ToolCard';

export default {
    components: {
        Layout,
        BannerHeader,
        ToolCard,
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
            title: trans('page.other.tool.index.title'),
        };
    },
};
</script>
