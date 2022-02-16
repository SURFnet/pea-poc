export default {
    methods: {
        /**
         * Scroll to the first input of this.$page.props.errors.
         *
         * Prerequisites
         * - The child component with the form must have a ref of "form"
         * - The grandchild components with the inputs must have a ref of their name (error key)
         */
        showFirstFormError() {
            const { errors } = this.$page.props;
            if (!errors) {
                return;
            }

            const formComponent = this.$refs.form;
            if (formComponent === undefined) {
                return;
            }

            const firstErrorKey = Object.keys(errors)[0];
            const inputComponent = formComponent.$refs[firstErrorKey];
            if (inputComponent === undefined) {
                return;
            }

            inputComponent.$el.scrollIntoView();

            const offset = 50;
            window.scrollBy(0, -offset);
        },
    },
};
