<template>
    <BaseModal :value="open" @close="close">
        <template #title>
            <h3 class="text-xl font-medium text-gray-900 | mb-4" v-text="trans('modal.status_legend.title')" />
        </template>

        <p class="font-normal text-sm | mb-4" v-text="trans('modal.status_legend.description')" />

        <AttributeList>
            <AttributeItem
                v-for="legendStatus in $page.props.instituteTool.legendStatuses"
                :key="legendStatus"
                :class="[legendStatus === status ? 'bg-blue-50' : '']"
            >
                <template #label>
                    <ToolStatus :status="legendStatus" :text="trans('institute.tool.statuses.' + legendStatus)" />
                </template>

                {{ trans('modal.status_legend.statuses.' + legendStatus) }}
            </AttributeItem>
        </AttributeList>
    </BaseModal>
</template>

<script>
import BaseModal from '@/components/BaseModal';
import AttributeList from '@/components/attribute/AttributeList';
import AttributeItem from '@/components/attribute/AttributeItem';
import ToolStatus from '@/components/ToolStatus';

export default {
    components: {
        BaseModal,
        AttributeList,
        AttributeItem,
        ToolStatus,
    },
    props: {
        open: {
            type: Boolean,
            default: true,
        },
        status: {
            type: String,
            required: true,
        },
    },
    methods: {
        /**
         * Responsible for closing.
         */
        close() {
            this.$emit('closed');
        },
    },
};
</script>
