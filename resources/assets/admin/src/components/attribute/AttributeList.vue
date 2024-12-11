<template>
    <div class="bg-white shadow sm:rounded-lg | overflow-hidden">
        <h3
            v-if="title"
            class="text-lg leading-6 font-medium text-gray-900"
            v-text="title"
        />

        <div class="border-t border-gray-200 | px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <slot />
            </dl>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        title: {
            type: String,
            default: null,
        },
    },
};
</script>
