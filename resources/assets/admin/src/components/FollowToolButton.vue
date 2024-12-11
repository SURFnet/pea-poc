<template>
    <div>
        <Btn
            type="button"
            variant="primary"
            :disabled="isImpersonating"
            @click="changeFollowStatus"
        >
            {{ buttonCaption }}

            <ToolTip
                class="font-medium"
                :text="tooltipText"
            />
        </Btn>
    </div>
</template>
<script>
import { router } from '@inertiajs/vue2';

import Btn from '@/components/Btn.vue';
import ToolTip from '@/components/ToolTip.vue';

export default {
    components: {
        Btn,
        ToolTip,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
        following: {
            type: Boolean,
            required: true,
        },
    },
    computed: {
        /**
         * Defines the caption of the button
         *
         * @returns {string}
         */
        buttonCaption() {
            return this.following
                ? trans('page.tool.follow.button.following')
                : trans('page.tool.follow.button.not-following');
        },

        /**
         * Defines the text for the tooltip
         *
         * @returns {string}
         */
        tooltipText() {
            return this.following
                ? trans('page.tool.follow.tooltip.following')
                : trans('page.tool.follow.tooltip.not-following');
        },
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
         * Tell the backend to change the following status of this tool
         *
         * @returns {void}
         */
        changeFollowStatus() {
            router.post(route('tool.change-following-status', this.tool));
        },
    },
};
</script>
