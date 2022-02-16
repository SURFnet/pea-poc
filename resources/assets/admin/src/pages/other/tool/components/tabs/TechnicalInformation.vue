<template>
    <div class="space-y-6">
        <h3
            class="text-2xl text-black font-bold"
            v-text="trans('page.other.tool.show.headings.technical_information')"
        />

        <!-- eslint-disable-next-line vue/no-v-html -->
        <p v-html="tool.description_short_display" />

        <AttributeList>
            <AttributeItem
                v-if="tool.supported_standards_display"
                :label="trans('page.other.tool.show.labels.supported_standards')"
            >
                {{ tool.supported_standards_display }}
            </AttributeItem>

            <AttributeItem
                v-if="tool.additional_standards"
                :label="trans('page.other.tool.show.labels.additional_standards')"
            >
                {{ tool.additional_standards }}
            </AttributeItem>

            <AttributeItem
                v-if="tool.authentication_methods_display"
                :label="trans('page.other.tool.show.labels.supported_authentication')"
            >
                {{ tool.authentication_methods_display }}
            </AttributeItem>

            <AttributeItem v-if="tool.stored_data_display" :label="trans('page.other.tool.show.labels.data_stored')">
                {{ tool.stored_data_display }}
            </AttributeItem>

            <AttributeItem :label="trans('page.other.tool.show.labels.storage_location')">
                <template v-if="tool.european_data_storage">
                    {{ trans('page.other.tool.show.options.europe') }}
                </template>
                <template v-else>
                    {{ trans('page.other.tool.show.options.outside_europe') }}
                </template>
            </AttributeItem>

            <AttributeItem :label="trans('page.other.tool.show.labels.surf_framework')">
                <span :class="classForRequirement(tool.surf_standards_framework_agreed)">
                    <template v-if="tool.surf_standards_framework_agreed">
                        {{ trans('page.other.tool.show.options.complies') }}
                    </template>
                    <template v-else>
                        {{ trans('page.other.tool.show.options.does_not_comply') }}
                    </template>
                </span>
            </AttributeItem>

            <AttributeItem :label="trans('page.other.tool.show.labels.processing_agreement')">
                <span :class="classForRequirement(tool.has_processing_agreement)">
                    <template v-if="tool.has_processing_agreement">
                        {{ trans('page.other.tool.show.options.present') }}
                    </template>
                    <template v-else>
                        {{ trans('page.other.tool.show.options.not_present') }}
                    </template>
                </span>
            </AttributeItem>
        </AttributeList>
    </div>
</template>

<script>
import AttributeList from '@/components/attribute/AttributeList';
import AttributeItem from '@/components/attribute/AttributeItem';

export default {
    components: {
        AttributeList,
        AttributeItem,
    },
    props: {
        tool: {
            type: Object,
            required: true,
        },
    },
    methods: {
        /**
         * Determines the class for a requirement.
         *
         * @param {boolean} value
         *
         * @returns {string}
         */
        classForRequirement(value) {
            if (value) {
                return 'text-green-600';
            }

            return 'text-red-600';
        },
    },
};
</script>
