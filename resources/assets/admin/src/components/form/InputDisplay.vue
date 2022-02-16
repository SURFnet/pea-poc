<template>
    <div class="space-y-2">
        <label class="block | text-sm font-source-sans text-gray-900 font-medium" v-text="label" />
        <p class="text-sm font-light text-gray-700" v-text="value" />
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: true,
        },
        value: {
            type: String,
            required: true,
        },
    },
};
</script>
