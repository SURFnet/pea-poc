<template>
    <div v-if="tool.institute.support_email_1 || tool.institute.support_email_2" class="border rounded p-4">
        <div class="flex items-center mb-4">
            <span class="mr-2">
                <img src="/svg/support-icon.svg" />
            </span>
            <h3 class="text-xl text-black" v-text="trans('page.our.tool.show.headings.support')" />
        </div>
        <div class="flex flex-col gap-1 text-sm">
            <div v-if="tool.institute.support_email_1">
                <a
                    :href="'mailto:' + tool.institute.support_email_1"
                    class="text-blue-500 hover:text-black"
                    v-text="tool.institute.support_title_1 || tool.institute.support_email_1"
                />
            </div>
            <div v-if="tool.institute.support_email_2">
                <a
                    :href="'mailto:' + tool.institute.support_email_2"
                    class="text-blue-500 hover:text-black"
                    v-text="tool.institute.support_title_2 || tool.institute.support_email_2"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
};
</script>
