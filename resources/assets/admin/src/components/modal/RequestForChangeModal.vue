<template>
    <BaseModal
        :value="open"
        size="lg"
        @close="close"
    >
        <template #title>
            <h3
                class="text-xl font-medium text-gray-900 | mb-4 line-clamp-2"
                v-text="trans('modal.request_for_change.title', { tool: tool.name })"
            />
        </template>

        <p
            class="font-normal text-sm | mb-4"
            v-text="trans('modal.request_for_change.description')"
        />

        <form
            class="space-y-6"
            @submit.prevent="submitForm"
        >
            <TextareaInput
                ref="input"
                v-model="form.request_for_change"
                :disabled="form.processing"
                :label="trans('modal.request_for_change.label')"
                :error="form.errors.request_for_change"
            />

            <div class="flex space-x-2 justify-end mt-6">
                <Btn
                    type="button"
                    variant="default"
                    :disabled="form.processing"
                    @click="close"
                >
                    {{ trans('modal.request_for_change.actions.cancel') }}
                </Btn>

                <Btn
                    type="submit"
                    variant="primary"
                    :disabled="form.processing"
                >
                    {{ trans('modal.request_for_change.actions.submit') }}
                </Btn>
            </div>
        </form>
    </BaseModal>
</template>

<script>
import { useForm } from '@inertiajs/vue2';

import BaseModal from '@/components/BaseModal';
import Btn from '@/components/Btn.vue';
import TextareaInput from '@/components/form/TextareaInput.vue';

export default {
    components: {
        TextareaInput,
        Btn,
        BaseModal,
    },
    props: {
        open: {
            type: Boolean,
            required: true,
        },
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
            form: useForm({
                request_for_change: '',
            }),
        };
    },
    watch: {
        /**
         * Watches for modal openings/closings
         *
         * @param {boolean} newValue
         */
        open(newValue) {
            if (newValue) {
                this.focusOnInput();
            }
        },
    },
    methods: {
        /**
         * Focuses on the first input on next tick
         */
        focusOnInput() {
            setTimeout(() => {
                this.$refs.input.$refs.input.focus();
            }, 0);
        },
        /**
         * Submits the form
         */
        submitForm() {
            this.form.post(route('information-manager.tool.request-for-change', { tool: this.tool }), {
                preserveScroll: true,
                onSuccess: () => {
                    this.reset();
                    this.close();
                },
            });
        },
        /**
         * Resets the form (errors + values)
         */
        reset() {
            this.form.reset();
            this.form.clearErrors();
        },
        /**
         * Responsible for closing.
         */
        close() {
            if (this.form.processing) {
                return;
            }

            this.$emit('closed');
        },
    },
};
</script>
