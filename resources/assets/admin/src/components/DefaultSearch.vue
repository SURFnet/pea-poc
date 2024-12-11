<template>
    <div class="rounded shadow-sm">
        <div class="relative | flex items-stretch flex-grow | focus-within:z-10">
            <div class="absolute inset-y-0 left-0 | flex items-center | pointer-events-none | pl-3">
                <FontAwesomeIcon icon="search" />
            </div>

            <input
                :value="value"
                type="search"
                class="w-full block | shadow-sm rounded border-gray-300 sm:text-sm | focus:ring-blue-400 focus:border-blue-500 | pl-10 mt-1"
                :placeholder="placeholder"
                @input="$emit('input', $event.target.value)"
            />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            default: null,
        },
        placeholder: {
            type: String,
            required: true,
        },
    },
};
</script>
