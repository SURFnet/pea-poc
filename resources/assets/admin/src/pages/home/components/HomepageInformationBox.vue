<template>
    <section
        v-if="title || body"
        class="bg-white py-8"
    >
        <div class="w-11/12 mx-auto">
            <h3
                v-if="title"
                class="text-xl text-black font-bold mb-3"
                v-text="title"
            />

            <!-- eslint-disable vue/no-v-html -->
            <div
                v-if="body"
                class="spacing-between-paragraphs"
                v-html="body"
            />
            <!-- eslint-enable vue/no-v-html -->
        </div>
    </section>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: null,
        },
        body: {
            type: String,
            default: null,
        },
    },
};
</script>
