<template>
    <BaseModal
        :value="open"
        @close="close"
    >
        <div class="text-center">
            <FontAwesomeIcon
                v-if="linkCopied"
                icon="check-circle"
                class="text-4xl text-green-600 | -mt-8"
            />

            <FontAwesomeIcon
                v-else
                icon="share-alt"
                class="text-4xl | -mt-8"
            />

            <p
                class="mb-6"
                v-text="infoText"
            />

            <div class="mb-4">
                <pre>{{ linkUrl }}</pre>
            </div>
        </div>
    </BaseModal>
</template>

<script>
import BaseModal from '@/components/BaseModal';

export default {
    components: {
        BaseModal,
    },
    props: {
        open: {
            type: Boolean,
            required: true,
        },
        infoText: {
            type: String,
            required: true,
        },
        linkCopied: {
            type: Boolean,
            required: true,
        },
        linkUrl: {
            type: String,
            default: null,
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
