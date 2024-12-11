<template>
    <PageContainer>
        <PageHeader :title="trans('page.content-manager.tool.log.title')" />

        <EditLog
            :tool="tool"
            :logs="logs"
        />
    </PageContainer>
</template>

<script>
import Layout from '@/layouts/AdminLayout';
import EditLog from '@/components/manager/EditLog.vue';
import PageHeader from '@/components/page/PageHeader.vue';
import PageContainer from '@/components/page/PageContainer.vue';

export default {
    components: { PageContainer, PageHeader, EditLog },
    layout: Layout,
    props: {
        tool: {
            type: Object,
            required: true,
        },
        logs: {
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
            title: trans('page.content-manager.tool.log.title'),
        };
    },
};
</script>
