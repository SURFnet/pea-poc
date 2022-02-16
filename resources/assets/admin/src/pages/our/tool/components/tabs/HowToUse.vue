<template>
    <div class="space-y-6">
        <h3 class="text-2xl text-black font-bold" v-text="trans('page.our.tool.show.headings.product_description')" />

        <!-- eslint-disable-next-line vue/no-v-html -->
        <p v-html="tool.description_short_display" />

        <div class="md:grid md:grid-cols-2 md:gap-20 | mb-16">
            <div class="md:col-span-1">
                <div v-if="tool.institute.description_1_display">
                    <p class="text-black font-bold" v-text="trans('page.our.tool.show.labels.how_it_works')" />

                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <p v-html="tool.institute.description_1_display" />
                </div>
            </div>

            <div class="md:col-span-1">
                <div v-if="tool.institute.description_1_image_filename">
                    <a
                        v-if="tool.institute.description_1_image_url"
                        :href="tool.institute.description_1_image_url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <div class="aspect-w-3 aspect-h-2 | mb-4">
                            <img
                                :src="tool.institute.description_1_image_url"
                                alt=""
                                class="object-cover | shadow-lg rounded-lg"
                            />
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="md:grid md:grid-cols-2 md:gap-20 | mb-16">
            <div class="md:col-span-1">
                <div v-if="tool.institute.description_2_display">
                    <p class="text-black font-bold" v-text="trans('page.our.tool.show.labels.tips')" />

                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <p v-html="tool.institute.description_2_display" />
                </div>
            </div>

            <div class="md:col-span-1">
                <div v-if="tool.institute.description_2_image_filename">
                    <a
                        v-if="tool.institute.description_2_image_url"
                        :href="tool.institute.description_2_image_url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <div class="aspect-w-3 aspect-h-2 | mb-4">
                            <img
                                :src="tool.institute.description_2_image_url"
                                alt=""
                                class="object-cover | shadow-lg rounded-lg"
                            />
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div v-if="tool.institute.extra_information_display" class="md:grid md:grid-cols-2 md:gap-20 | mb-16">
            <div class="md:col-span-1">
                <div>
                    <p
                        v-if="tool.institute.extra_information_title"
                        class="text-black font-bold"
                        v-text="tool.institute.extra_information_title"
                    />

                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <p v-html="tool.institute.extra_information_display" />
                </div>
            </div>
        </div>

        <template v-if="tool.institute.manual_url_1 || tool.institute.manual_url_2">
            <PageDivider />

            <div class="space-y-4">
                <p class="text-black font-bold" v-text="trans('page.our.tool.show.labels.manuals')" />

                <ExternalLink
                    v-if="tool.institute.manual_url_1"
                    :url="tool.institute.manual_url_1"
                    :title="tool.institute.manual_title_1 || tool.institute.manual_url_1"
                />

                <ExternalLink
                    v-if="tool.institute.manual_url_2"
                    :url="tool.institute.manual_url_2"
                    :title="tool.institute.manual_title_2 || tool.institute.manual_url_2"
                />
            </div>
        </template>

        <template v-if="tool.institute.video_url_1 || tool.institute.video_url_2">
            <PageDivider />

            <div class="space-y-4">
                <p class="text-black font-bold" v-text="trans('page.our.tool.show.labels.instruction_videos')" />

                <ExternalLink
                    v-if="tool.institute.video_url_1"
                    :url="tool.institute.video_url_1"
                    :title="tool.institute.video_title_1 || tool.institute.video_url_1"
                />

                <ExternalLink
                    v-if="tool.institute.video_url_2"
                    :url="tool.institute.video_url_2"
                    :title="tool.institute.video_title_2 || tool.institute.video_url_2"
                />
            </div>
        </template>
    </div>
</template>

<script>
import PageDivider from '@/components/page/PageDivider';
import ExternalLink from '@/pages/our/tool/components/ExternalLink';

export default {
    components: {
        PageDivider,
        ExternalLink,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
};
</script>
