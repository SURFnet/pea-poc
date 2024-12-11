<template>
    <a
        :href="url"
        target="_blank"
        rel="noopener noreferrer"
        class="block | text-blue-500 hover:text-black"
    >
        <FontAwesomeIcon
            class="text-black | mr-2"
            icon="arrow-right"
        />

        <span v-text="title" />
    </a>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
    },
};
</script>
