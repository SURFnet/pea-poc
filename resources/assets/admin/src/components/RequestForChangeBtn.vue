<template>
    <div>
        <RequestForChangeModal
            :open="modalIsOpen"
            :tool="tool"
            @closed="closeModal"
        />

        <Btn
            type="button"
            variant="default-dark"
            :disabled="isImpersonating"
            @click="openModal"
        >
            {{ trans('page.shared.tool.actions.request_for_change') }}
        </Btn>
    </div>
</template>

<script>
import Btn from '@/components/Btn.vue';
import RequestForChangeModal from '@/components/modal/RequestForChangeModal.vue';

export default {
    components: { RequestForChangeModal, Btn },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            modalIsOpen: false,
        };
    },
    computed: {
        /**
         * Determines if the current user is impersonating another institute
         *
         * @returns {boolean}
         */
        isImpersonating() {
            return this.$page.props.isImpersonating === true;
        },
    },
    methods: {
        /**
         * Opens the modal
         */
        openModal() {
            this.modalIsOpen = true;
        },
        /**
         * Closes the modal
         */
        closeModal() {
            this.modalIsOpen = false;
        },
    },
};
</script>
