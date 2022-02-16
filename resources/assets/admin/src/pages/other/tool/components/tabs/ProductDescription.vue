<template>
    <div class="space-y-6">
        <h3 class="text-2xl text-black font-bold" v-text="trans('page.other.tool.show.headings.product_description')" />

        <!-- eslint-disable-next-line vue/no-v-html -->
        <p v-html="tool.description_short_display" />

        <div class="md:grid md:grid-cols-2 md:gap-20 | mb-16">
            <div class="md:col-span-1">
                <div v-if="tool.description_long_1_display">
                    <p class="text-black font-bold" v-text="trans('page.other.tool.show.labels.description_tool')" />

                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <p v-html="tool.description_long_1_display" />
                </div>
            </div>

            <div class="md:col-span-1">
                <div v-if="tool.description_1_image_filename">
                    <a
                        v-if="tool.description_1_image_url"
                        :href="tool.description_1_image_url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <div class="aspect-w-3 aspect-h-2 | mb-4">
                            <img
                                :src="tool.description_1_image_url"
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
                <div v-if="tool.description_long_2_display">
                    <p class="text-black font-bold" v-text="trans('page.other.tool.show.labels.examples_of_usage')" />

                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <p v-html="tool.description_long_2_display" />
                </div>
            </div>

            <div class="md:col-span-1">
                <div v-if="tool.description_2_image_filename">
                    <a
                        v-if="tool.description_2_image_url"
                        :href="tool.description_2_image_url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <div class="aspect-w-3 aspect-h-2 | mb-4">
                            <img
                                :src="tool.description_2_image_url"
                                alt=""
                                class="object-cover | shadow-lg rounded-lg"
                            />
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div v-if="tool.info_url">
            <h3 class="text-black font-bold" v-text="trans('page.other.tool.show.labels.more_info')" />

            <a :href="tool.info_url" target="_blank" rel="noopener noreferrer">{{ tool.info_url }}</a>
        </div>

        <ReviewList :tool="tool">
            <ReviewComponent v-for="experience in experiences" :key="experience.id" :experience="experience" />
        </ReviewList>
    </div>
</template>

<script>
import ReviewList from '@/components/ReviewList';
import ReviewComponent from '@/components/ReviewComponent';

export default {
    components: {
        ReviewList,
        ReviewComponent,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
        experiences: {
            type: Array,
            required: true,
        },
    },
};
</script>
