<template>
    <div v-if="text" class="text-sm text-gray-500">
        {{ text }}
    </div>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            default: null,
        },
    },
};
</script>
