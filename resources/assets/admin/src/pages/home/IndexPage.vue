<template>
    <div class="container | page | space-y-6">
        <BannerHeader
            :title="trans('page.home.index.section-header.title')"
            :subtitle="
                trans('page.home.index.section-header.subtitle', {
                    institute: $page.props.currentUser.institute.full_name,
                })
            "
            :image="$page.props.currentUser.institute.banner_url"
            :search-placeholder="trans('page.shared.section-header.search-our')"
        />

        <div class="lg:grid grid-cols-4 gap-4">
            <CategoryTile v-for="category in categories" :key="category.id" :category="category" class="mb-4 lg:mb-0" />
        </div>
    </div>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';

import BannerHeader from '@/components/page/BannerHeader';
import CategoryTile from '@/components/CategoryTile';

export default {
    components: {
        BannerHeader,
        CategoryTile,
    },
    layout: Layout,
    props: {
        categories: {
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
            title: trans('page.home.index.title'),
        };
    },
};
</script>
