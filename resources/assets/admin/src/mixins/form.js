export default {
    props: {
        form: {
            type: Object,
            required: true,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            internalForm: this.form,
        };
    },
    watch: {
        internalForm: {
            /**
             * Handles the change.
             */
            handler() {
                this.$emit('update:form', this.internalForm);
            },
            deep: true,
        },
    },
};
