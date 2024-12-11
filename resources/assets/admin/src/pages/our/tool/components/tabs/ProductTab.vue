<template>
    <div class="space-y-6">
        <TabHeading :text="trans('page.our.tool.show.tabs.product')" />

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

        <div v-if="tool.institute.why_unfit">
            <TabSubheading
                :text="trans('institute.tool.attributes.why_unfit')"
                :tooltip="tool.institute.tooltips.why_unfit"
            />

            <!-- eslint-disable-next-line vue/no-v-html -->
            <p v-html="tool.institute.why_unfit" />
        </div>

        <div v-if="tool.institute.conditions">
            <TabSubheading
                :text="trans('institute.tool.attributes.conditions')"
                :tooltip="tool.institute.tooltips.conditions"
            />

            <WysiwygOutput :value="tool.institute.conditions" />
        </div>

        <div class="md:grid md:grid-cols-2 md:gap-20 | mb-16">
            <div class="md:col-span-1">
                <TabSubheading :text="trans('tool.attributes.description_long')" />

                <WysiwygOutput :value="tool.description_long" />
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

        <div
            v-for="customField in filterCustomFields(tool.institute.custom_fields, 'product')"
            :key="customField.id"
        >
            <TabSubheading :text="customField.title" />

            <WysiwygOutput :value="customField.value" />
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
                :text="trans('page.our.tool.show.headings.request_a_change')"
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
import RequestForChangeBtn from '@/components/RequestForChangeBtn.vue';
import { longDatetime } from '@/helpers/datetime';
import TabHeading from '@/components/TabHeading.vue';
import TabSubheading from '@/components/TabSubheading.vue';
import LightBox from '@/components/LightBox.vue';
import { filterCustomFields } from '@/helpers/filter-custom-fields';
import WysiwygOutput from '@/components/WysiwygOutput';
import Url from '@/components/Url.vue';

export default {
    components: {
        Url,
        LightBox,
        TabHeading,
        TabSubheading,
        RequestForChangeBtn,
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
    methods: { longDatetime, filterCustomFields },
};
</script>
