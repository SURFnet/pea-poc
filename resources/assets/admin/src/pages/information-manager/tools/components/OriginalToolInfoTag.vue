<template>
    <span
        class="inline-block mb-1.5 rounded bg-gray-500 cursor-not-allowed py-1 px-2.5 max-w-full truncate text-center text-sm text-white"
        v-text="tagText"
    />
</template>

<script>
export default {
    name: 'OriginalToolInfoTag',
    props: {
        tagText: {
            type: String,
            required: true,
        },
    },
};
</script>
