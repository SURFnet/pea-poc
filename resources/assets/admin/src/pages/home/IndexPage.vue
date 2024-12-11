<template>
    <div class="container | page | space-y-8">
        <BannerHeader
            :title="trans('page.home.index.section-header.title')"
            :subtitle="
                trans('page.home.index.section-header.subtitle', {
                    institute: $page.props.currentUser.institute.full_name,
                })
            "
            :image="$page.props.currentUser.institute.banner_url"
            :search-placeholder="trans('page.shared.section-header.search-tools')"
        />

        <div class="lg:grid grid-cols-4 gap-4">
            <CategoryTile
                v-for="category in categories"
                :key="category.id"
                :category="category"
                class="mb-4 lg:mb-0"
            />
        </div>

        <HomepageInformationBox
            :title="homepageInformation?.title"
            :body="homepageInformation?.body"
        />
    </div>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';

import BannerHeader from '@/components/page/BannerHeader';
import CategoryTile from '@/components/CategoryTile';
import HomepageInformationBox from '@/pages/home/components/HomepageInformationBox.vue';
import { mapStores } from 'pinia';
import { useToolFilterStore } from '@/stores/tool-filter';

export default {
    components: {
        BannerHeader,
        CategoryTile,
        HomepageInformationBox,
    },
    layout: Layout,
    props: {
        categories: {
            type: Array,
            default: null,
        },
        homepageInformation: {
            type: Object,
            default: null,
        },
    },
    computed: {
        ...mapStores(useToolFilterStore),
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
    /**
     * Runs code after an instance is mounted.
     */
    mounted() {
        // eslint-disable-next-line vue/no-undef-properties
        this.toolFilterStore.reset();
    },
};
</script>
