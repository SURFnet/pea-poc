<template>
    <div
        class="h-12 w-12 sm:h-10 sm:w-10 | flex-shrink-0 flex items-center justify-center | rounded-full | mx-auto sm:mx-0"
        :class="background"
    >
        <FontAwesomeIcon :class="color" :icon="icon" transform="up-1" />
    </div>
</template>

<script>
export default {
    props: {
        icon: {
            type: Array,
            required: true,
        },
        background: {
            type: String,
            default: '',
        },
        color: {
            type: String,
            required: true,
        },
    },
};
</script>
