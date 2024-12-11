<template>
    <img
        :src="logo"
        class="max-w-full"
    />
</template>

<script>
export default {
    props: {
        logo: {
            type: String,
            default: '/dist/admin/images/surf-logo.svg',
        },
    },
};
</script>
