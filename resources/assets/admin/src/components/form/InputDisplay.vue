<template>
    <div class="space-y-2">
        <InputLabel>
            {{ label }}
        </InputLabel>

        <p
            class="text-sm font-light text-gray-700"
            v-text="value"
        />
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel.vue';

export default {
    components: { InputLabel },
    props: {
        label: {
            type: String,
            required: true,
        },
        value: {
            type: String,
            required: true,
        },
    },
};
</script>
