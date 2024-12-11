<template>
    <a
        :href="link"
        :target="target"
        :rel="rel"
        v-text="linkDisplay"
    />
</template>

<script>
export default {
    props: {
        link: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            default: null,
        },
    },
    computed: {
        /**
         * Return the label when its set, else the url when it is an url otherwise strip mailto
         * @returns {string}
         */
        linkDisplay() {
            if (this.label) {
                return this.label;
            }

            if (this.isUrl) {
                return this.link;
            }

            return this.link.replace('mailto:', '');
        },
        /**
         * return the target when it is an url
         * @returns {null|string}
         */
        target() {
            return this.isUrl ? '_blank' : null;
        },
        /**
         * Return the rel when it is an url
         * @returns {null|string}
         */
        rel() {
            return this.isUrl ? 'noreferrer noopener' : null;
        },
        /**
         * Check if the link is an url
         * @returns {boolean}
         */
        isUrl() {
            return this.link.startsWith('http');
        },
    },
};
</script>
