<template>
    <div class="flex flex-col gap-y-1">
        <InputLabel large-label>
            {{ label }}
        </InputLabel>

        <div class="flex gap-x-1 flex-wrap">
            <template v-if="tags.length > 0">
                <OriginalToolInfoTag
                    v-for="tag in tags"
                    :key="tag.id"
                    :tag-text="tag.name"
                />
            </template>

            <span
                v-else
                class="sm:text-sm italic"
                v-text="`(${trans('page.our.tool.show.none')})`"
            />
        </div>
    </div>
</template>

<script>
import OriginalToolInfoTag from '@/pages/information-manager/tools/components/OriginalToolInfoTag.vue';
import InputLabel from '@/components/form/shared/InputLabel.vue';

export default {
    name: 'InfoTagGroup',
    components: { InputLabel, OriginalToolInfoTag },
    props: {
        label: {
            type: String,
            required: true,
        },
        tags: {
            type: Array,
            required: true,
        },
    },
};
</script>
<style scoped></style>
