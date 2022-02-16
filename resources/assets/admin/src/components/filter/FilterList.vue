<template>
    <nav class="space-y-1">
        <h3 class="text-gray-900 text-lg leading-6 font-semibold" v-text="title" />

        <slot />
    </nav>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
    },
};
</script>
