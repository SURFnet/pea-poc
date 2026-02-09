<template>
    <div>
        <Btn
            type="button"
            variant="primary"
            @click="copyToClipboard"
        >
            <span
                class="tooltip"
                :data-text="infoText"
            >
                <FontAwesomeIcon
                    v-if="linkCopied"
                    icon="check-circle"
                    class="mr-2"
                />

                <FontAwesomeIcon
                    v-else
                    icon="share-alt"
                    class="opacity-40 | mr-2"
                />

                Share
            </span>
        </Btn>

        <LinkCopied
            :open="modalOpen"
            :info-text="infoText"
            :link-copied="linkCopied"
            :link-url="link"
            @closed="modalOpen = false"
        />
    </div>
</template>

<script>
import Btn from '@/components/Btn.vue';
import LinkCopied from '@/components/modal/LinkCopied.vue';

export default {
    components: {
        Btn,
        LinkCopied,
    },
    props: {
        link: {
            type: String,
            required: true,
        },
    },
    /**
     * @returns {object}
     */
    data() {
        return {
            modalOpen: false,
            linkCopied: false,
        };
    },
    computed: {
        /**
         * Defines the caption of the button
         *
         * @returns {string}
         */
        infoText() {
            return this.linkCopied ? trans('modal.copy_link.link_copied') : trans('modal.copy_link.copy_link');
        },
    },
    methods: {
        /**
         * The API `navigator.clipboard` only works in secure contexts (https).
         * See: https://developer.mozilla.org/en-US/docs/Web/API/Navigator/clipboard
         */
        async copyToClipboard() {
            try {
                await navigator.clipboard.writeText(this.link);
                this.linkCopied = true;
                this.modalOpen = true;
            } catch (err) {
                this.modalOpen = true;
            }
        },
    },
};
</script>

<style scoped>
.tooltip {
    position: relative;
}

.tooltip::before {
    display: none;
    position: absolute;
    content: attr(data-text);
    z-index: 10;
    margin-top: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    background: #f4f6f8;
    padding: 0.625rem;
    width: 80vw;
    text-align: center;
    color: #2e2e2e;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

@media screen and (min-width: 768px) {
    .tooltip::before {
        top: 0;
        left: 100%;
        margin-left: 0.875rem;
        transform: translateY(-50%);
        width: 12rem;
    }
}

.tooltip:hover::before,
.tooltip:active::before {
    display: block;
}
</style>
