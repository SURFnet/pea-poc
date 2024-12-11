<template>
    <InertiaLink
        :href="url"
        class="relative | w-full md:w-2/3 flex flex-row | bg-white border border-gray-200 shadow-lg | text-black rounded-md cursor-pointer hover:no-underline hover:border-blue-500 | text-left | transition-all | p-6 space-x-4 | group"
    >
        <img
            :src="tool.logo_url"
            :alt="tool.name"
            class="h-28 w-28 | border-2 border-gray-200 p-0.5"
        />

        <div>
            <div class="flex justify-between gap-2">
                <div>
                    <p
                        class="font-semibold group-hover:text-blue-500 | mb-1 | line-clamp-1"
                        v-text="tool.name"
                    />

                    <div
                        v-if="tool.total_experiences > 0"
                        class="text-xs text-gray-400 | mb-1"
                        v-text="
                            trans_choice('page.shared.tool.card.total_experiences', tool.total_experiences, {
                                count: tool.total_experiences,
                            })
                        "
                    />
                </div>

                <ToolStatusInfo
                    class="shrink-0"
                    :status="tool.institute?.status ?? 'unrated'"
                    :text="tool.institute?.status_display ?? trans('institute.tool.statuses.unrated')"
                />
            </div>

            <p
                class="font-light | line-clamp-2"
                v-text="tool.description_short_stripped_tags"
            />
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
