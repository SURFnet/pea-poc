<template>
    <div class="text-sm text-red-700">
        {{ error }}
    </div>
</template>

<script>
export default {
    props: {
        error: {
            type: String,
            required: true,
        },
    },
};
</script>
