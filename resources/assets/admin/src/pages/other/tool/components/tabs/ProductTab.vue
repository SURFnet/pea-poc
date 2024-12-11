<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.other.tool.show.tabs.product')" />

        <div v-if="tool.supplier">
            <TabSubheading :text="trans('tool.attributes.supplier')" />

            <Url
                v-if="tool.supplier_url"
                :link="tool.supplier_url"
                :label="tool.supplier"
            />

            <div
                v-else
                v-text="tool.supplier"
            />
        </div>

        <div class="md:grid md:grid-cols-2 md:gap-20 | mb-16">
            <div class="md:col-span-1">
                <template v-if="tool.description_long">
                    <TabSubheading :text="trans('tool.attributes.description_long')" />

                    <WysiwygOutput :value="tool.description_long" />
                </template>
            </div>

            <div class="md:col-span-1">
                <div
                    v-if="tool.image_1_filename"
                    class="aspect-w-3 aspect-h-2 | mb-4"
                >
                    <LightBox
                        unique-id="description_1_image"
                        :image-url="tool.image_1_url"
                    />
                </div>

                <div
                    v-if="tool.image_2_filename"
                    class="aspect-w-3 aspect-h-2 | mb-4"
                >
                    <LightBox
                        unique-id="description_2_image"
                        :image-url="tool.image_2_url"
                    />
                </div>
            </div>
        </div>

        <div v-if="tool.addons">
            <TabSubheading
                :text="trans('tool.attributes.addons')"
                :tooltip="trans('institute.tool.tooltip.addons')"
            />

            <WysiwygOutput :value="tool.addons" />
        </div>

        <div>
            <TabSubheading
                :text="trans('tool.attributes.updated_at')"
                :tooltip="trans('institute.tool.tooltip.updated_at')"
            />

            <time
                :datetime="tool.updated_at"
                v-text="longDatetime(tool.updated_at)"
            />
        </div>

        <div v-if="tool.permissions.submit_request_for_change">
            <TabSubheading
                class="mb-1"
                :text="trans('page.other.tool.show.headings.request_a_change')"
            />

            <RequestForChangeBtn :tool="tool" />
        </div>

        <ReviewList :tool="tool">
            <ReviewComponent
                v-for="experience in experiences"
                :key="experience.id"
                :experience="experience"
            />
        </ReviewList>
    </div>
</template>

<script>
import ReviewList from '@/components/ReviewList';
import ReviewComponent from '@/components/ReviewComponent';
import LightBox from '@/components/LightBox.vue';
import RequestForChangeBtn from '@/components/RequestForChangeBtn.vue';
import { longDatetime } from '@/helpers/datetime';
import TabHeading from '@/components/TabHeading.vue';
import TabSubheading from '@/components/TabSubheading.vue';
import WysiwygOutput from '@/components/WysiwygOutput';
import Url from '@/components/Url.vue';

export default {
    components: {
        Url,
        TabHeading,
        TabSubheading,
        RequestForChangeBtn,
        LightBox,
        ReviewList,
        ReviewComponent,
        WysiwygOutput,
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
    methods: { longDatetime },
};
</script>
