<template>
    <div class="w-64 h-20 | flex flex-row items-center | rounded-md border border-gray-300 | py-2 px-3 mb-2">
        <div class="h-14 w-14 flex-shrink-0 | flex justify-center items-center | border border-gray-200 | p-1 mr-3">
            <img :src="image" :alt="name" />
        </div>
        <p class="font-normal text-gray-900" v-text="name" />
    </div>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true,
        },
        image: {
            type: String,
            default: 'dist/admin/images/placeholder.png',
        },
    },
};
</script>
