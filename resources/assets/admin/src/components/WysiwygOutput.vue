<template>
    <!-- eslint-disable vue/no-v-html -->
    <div
        class="wysiwyg"
        v-html="value"
    />
    <!-- eslint-enable vue/no-v-html -->
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            default: null,
        },
    },
};
</script>
