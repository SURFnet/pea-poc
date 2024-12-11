<template>
    <div
        v-if="pendingEdit"
        class="flex gap-4 items-center mb-4 md:mb-0 bg-yellow-200 py-2.5 px-6 w-fit min-h-[60px] font-semibold"
    >
        <div>
            <FontAwesomeIcon
                icon="exclamation-triangle"
                size="lg"
                class="text-yellow-500"
            />
        </div>

        <div>
            <span v-text="trans('alert.pending_edit.part_1')" />

            <span
                class="font-bold"
                v-text="pendingEdit.user.name"
            />

            <span v-text="trans('alert.pending_edit.part_2')" />

            <time
                class="font-bold"
                :datetime="pendingEdit.created_at"
                v-text="longDatetime(pendingEdit.created_at)"
            />
        </div>
    </div>
</template>

<script>
import { longDatetime } from '@/helpers/datetime.js';

export default {
    name: 'AlertBox',

    props: {
        pendingEdit: {
            type: Object,
            default: null,
        },
    },
    methods: { longDatetime },
};
</script>
