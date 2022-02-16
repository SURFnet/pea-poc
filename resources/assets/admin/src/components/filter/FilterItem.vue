<template>
    <label
        :for="`filter-${type}-${item.id}`"
        class="font-source-sans text-gray-900 hover:text-gray-900 hover:bg-gray-50 group rounded-md | flex items-center text-sm font-normal | px-3 py-1"
    >
        <slot />

        {{ item.name }}
    </label>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true,
        },
        item: {
            type: Object,
            required: true,
        },
    },
};
</script>
