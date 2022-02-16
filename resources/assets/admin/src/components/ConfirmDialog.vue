<template>
    <Modal :size="size" :value="open" @close="close()">
        <div class="text-center | text-gray-900">
            <div class="sm:flex sm:items-start | px-4 p-6 pt-5">
                <ModalIcon :icon="['fas', 'exclamation-triangle']" color="text-red-600" background="bg-red-100" />

                <div class="w-full | text-center sm:text-left | mt-3 sm:mt-0 sm:ml-4">
                    <h3 v-if="dialogTitle" class="text-lg leading-6 font-medium text-gray-900" v-text="dialogTitle" />

                    <div class="max-h-1/4vh overflow-y-auto scrolling-touch | mt-2">
                        <p v-text="dialogText" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end | space-x-4">
                <Btn type="button" variant="default-dark" @click="close()" v-text="trans('confirm.actions.cancel')" />

                <Btn
                    type="submit"
                    variant="primary"
                    :disabled="!canConfirm"
                    @click="confirm()"
                    v-text="trans('confirm.actions.confirm')"
                />
            </div>
        </div>
    </Modal>
</template>

<script>
import Modal from '@/components/BaseModal';
import ModalIcon from '@/components/ModalIcon';
import Btn from '@/components/Btn';

export default {
    components: { Modal, ModalIcon, Btn },
    props: {
        open: {
            type: Boolean,
            required: true,
        },
        canConfirm: {
            type: Boolean,
            default: true,
        },
        title: {
            type: String,
            default: null,
        },
        text: {
            type: String,
            default: null,
        },
        size: {
            type: String,
            default: 'sm',
        },
    },

    computed: {
        /**
         * Get the dialog title.
         *
         * @returns {string}
         */
        dialogTitle() {
            if (this.title) {
                return this.title;
            }

            return trans('confirm.title');
        },

        /**
         * Get the dialog text.
         *
         * @returns {string}
         */
        dialogText() {
            if (this.text) {
                return this.text;
            }

            return trans('confirm.text');
        },
    },

    methods: {
        /**
         * Responsible for closing.
         */
        close() {
            this.$emit('closed');
        },

        /**
         * Responsible for confirming.
         */
        confirm() {
            this.$emit('confirmed');
        },
    },
};
</script>
