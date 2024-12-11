<template>
    <div class="flex items-center justify-center | rounded-full bg-red-400 | w-4 h-4">
        <p
            class="text-white text-small"
            v-text="amount"
        />
    </div>
</template>

<script>
export default {
    props: {
        amount: {
            type: Number,
            default: null,
        },
    },
};
</script>
