<template>
    <footer class="text-center text-xs | py-4">
        <span class="font-semibold">{{ appName }}</span> © {{ new Date().getFullYear() }}
    </footer>
</template>

<script>
export default {
    props: {
        appName: {
            type: String,
            required: true,
        },
    },
};
</script>
