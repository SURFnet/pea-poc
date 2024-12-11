<template>
    <div>
        <InputLabel large-label>
            {{ label }}
        </InputLabel>

        <div class="sm:text-sm">
            <!-- eslint-disable vue/no-v-html -->
            <span
                v-if="html"
                v-html="html"
            />
            <!-- eslint-enable vue/no-v-html -->

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
        html: {
            type: String,
            default: null,
        },
    },
};
</script>
