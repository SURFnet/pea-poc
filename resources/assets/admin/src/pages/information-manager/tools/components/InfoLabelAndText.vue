<template>
    <div>
        <InputLabel large-label>
            {{ label }}
        </InputLabel>

        <div class="sm:text-sm">
            <span
                v-if="text"
                v-text="text"
            />

            <span
                v-else
                class="italic"
                v-text="`(${trans('page.our.tool.show.none')})`"
            />
        </div>
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel';

export default {
    components: {
        InputLabel,
    },
    props: {
        label: {
            type: String,
            required: true,
        },
        text: {
            type: String,
            default: null,
        },
    },
};
</script>
