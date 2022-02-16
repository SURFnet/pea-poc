<template>
    <BaseModal :value="open" @close="close">
        <template #title>
            <h3 class="text-xl font-medium text-gray-900 | mb-4" v-text="trans('modal.share_experience.title')" />
        </template>

        <form class="space-y-6" @submit.prevent="submit">
            <ExperienceForm :form.sync="form" />

            <FormFooter align="end" class="mt-6">
                <Btn
                    type="button"
                    variant="default"
                    @click="close"
                    v-text="trans('modal.share_experience.actions.cancel')"
                />

                <Btn type="submit" variant="primary" v-text="trans('modal.share_experience.actions.share')" />
            </FormFooter>
        </form>
    </BaseModal>
</template>

<script>
import BaseModal from '@/components/BaseModal';
import FormFooter from '@/components/FormFooter';
import Btn from '@/components/Btn';

import ExperienceForm from '@/components/modal/components/ExperienceForm';

export default {
    components: {
        BaseModal,
        FormFooter,
        Btn,
        ExperienceForm,
    },
    props: {
        open: {
            type: Boolean,
            default: true,
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
            form: this.$inertia.form({
                rating: 0,
                title: null,
                message: null,
            }),
        };
    },
    methods: {
        /**
         * Responsible for closing.
         */
        close() {
            this.form.rating = 0;
            this.form.title = null;
            this.form.message = null;

            this.$emit('closed');
        },
        /**
         * Submits the form and closes the modal
         */
        submit() {
            this.form.post(route('teacher.tool.experience.store', { tool: this.tool }), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                },
            });
        },
    },
};
</script>
