<template>
    <BaseModal
        :value="open"
        @close="close()"
    >
        <div class="text-center">
            <p
                class="mb-6"
                v-text="trans('action.add_url')"
            />

            <div class="mb-4">
                <TextInput
                    ref="input"
                    v-model="url"
                    type="url"
                />

                <span
                    v-if="errorMessage"
                    class="text-xs text-red-600 mt-2"
                    v-text="errorMessage"
                />
            </div>

            <div class="flex justify-end | space-x-4">
                <Btn
                    type="button"
                    variant="default-dark"
                    @click.prevent="close()"
                >
                    {{ trans('action.cancel') }}
                </Btn>

                <Btn
                    type="submit"
                    variant="primary"
                    @click.prevent="confirm()"
                >
                    {{ trans('action.add') }}
                </Btn>
            </div>
        </div>
    </BaseModal>
</template>

<script>
import BaseModal from '@/components/BaseModal';
import TextInput from '@/components/form/TextInput';
import Btn from '@/components/Btn';

export default {
    components: {
        BaseModal,
        TextInput,
        Btn,
    },
    props: {
        open: {
            type: Boolean,
            required: true,
        },
        previousUrl: {
            type: String,
            default: null,
        },
    },
    /** @returns {object} */
    data() {
        return {
            url: null,
            errorMessage: null,
        };
    },
    watch: {
        /**
         * @param {string} value
         */
        previousUrl(value) {
            this.url = value;
        },

        /**
         * @param {boolean} value
         */
        open(value) {
            if (value !== true) {
                return;
            }

            setTimeout(() => this.$refs.input.$refs.input.focus(), 200);
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
         * Determines if the entered URL is valid.
         *
         * @returns {boolean}
         */
        isValidUrl() {
            if (!this.url) {
                return false;
            }

            // eslint-disable-next-line
            const regex = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/gm;

            return this.url.match(regex);
        },

        /**
         * Responsible for confirming.
         */
        confirm() {
            if (!this.isValidUrl()) {
                this.errorMessage = trans('validation.wysiwyg.url');

                return;
            }

            this.errorMessage = null;
            this.$emit('url', this.url);
            this.url = '';
            this.close();
        },
    },
};
</script>
