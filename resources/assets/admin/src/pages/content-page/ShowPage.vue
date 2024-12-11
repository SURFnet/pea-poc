<template>
    <PageContainer>
        <PageCard>
            <PageHeader :title="contentPage.title" />

            <!-- eslint-disable vue/no-v-html -->
            <div v-html="contentPage.body" />
            <!-- eslint-enable vue/no-v-html -->
        </PageCard>
    </PageContainer>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';

import PageHeader from '@/components/page/PageHeader.vue';
import PageContainer from '@/components/page/PageContainer.vue';
import PageCard from '@/components/page/PageCard.vue';

export default {
    components: {
        PageCard,
        PageContainer,
        PageHeader,
    },
    layout: Layout,
    props: {
        contentPage: {
            type: Object,
            required: true,
        },
    },
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.about.index.title'),
        };
    },
};
</script>

<style scoped>
.thumbnail-container {
    min-height: 250px;
}
</style>
