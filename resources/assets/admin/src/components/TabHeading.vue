<template>
    <h3
        class="text-surf-h3 leading-surf-h3 text-surf-black font-surf-h3"
        v-text="text"
    />
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            required: true,
        },
    },
};
</script>
