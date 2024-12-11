<template>
    <InertiaLink
        :href="url"
        class="relative | w-full h-20 flex flex-row | bg-white border border-gray-200 shadow-lg | text-black rounded-md cursor-pointer hover:no-underline hover:border-blue-500 | text-left | transition-all | py-2 px-3 mb-2 | group"
    >
        <img
            :src="tool.logo_url"
            :alt="tool.name"
            class="h-14 w-14 flex-shrink-0 | flex justify-center items-center | border border-gray-200 | p-1 mr-3"
        />

        <div>
            <div class="flex flex-col justify-between gap-2">
                <div>
                    <p
                        class="font-semibold group-hover:text-blue-500 | mb-1 | line-clamp-1"
                        v-text="tool.name"
                    />
                </div>

                <ToolStatusInfo
                    class="shrink-0"
                    :status="tool.institute?.status ?? 'unrated'"
                    :text="tool.institute?.status_display ?? trans('institute.tool.statuses.unrated')"
                />
            </div>
        </div>
    </InertiaLink>
</template>

<script>
import ToolStatusInfo from '@/components/ToolStatusInfo';

export default {
    components: {
        ToolStatusInfo,
    },
    props: {
        url: {
            type: String,
            required: true,
        },
        tool: {
            type: Object,
            default: null,
        },
    },
};
</script>
