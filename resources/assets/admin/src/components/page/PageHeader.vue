<template>
    <header class="lg:flex lg:items-center lg:justify-between | mb-8">
        <div class="flex-1 min-w-0">
            <h2
                class="text-4xl leading-10 text-gray-900 font-semibold | sm:text-2xl sm:leading-10 sm:truncate"
                v-text="title"
            />
        </div>

        <div class="mt-5 flex lg:mt-0 lg:ml-4 | space-x-4">
            <slot />

            <BaseDropdown
                v-if="$slots.more"
                position="right"
            >
                <template #button-content="{ isOpen, toggle }">
                    <button
                        :class="{ 'is-active': isOpen }"
                        @click="toggle"
                    >
                        <FontAwesomeIcon
                            icon="ellipsis-h"
                            size="xs"
                            fixed-width
                        />
                    </button>
                </template>

                <slot name="more" />
            </BaseDropdown>
        </div>
    </header>
</template>

<script>
import BaseDropdown from '@/components/BaseDropdown';

export default {
    components: {
        BaseDropdown,
    },
    props: {
        title: {
            type: String,
            required: true,
        },
    },
};
</script>
