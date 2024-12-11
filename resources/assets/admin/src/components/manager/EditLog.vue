<template>
    <div class="flex flex-col gap-4">
        <PageCard>
            <div class="flex flex-row gap-4 items-center mb-4">
                <EntityIcon
                    size="md"
                    :image="tool.logo_url"
                    :text="tool.name"
                />

                <h3
                    class="text-xl text-black"
                    v-text="tool.name"
                />
            </div>

            <table
                v-if="logs.data.length"
                class="bg-gray-100 w-full"
            >
                <thead>
                    <tr class="text-left border-y-2 border-white border-solid">
                        <th
                            class="py-2 px-3 w-1/2"
                            v-text="trans('log.attributes.edited-on')"
                        />
                        <th
                            class="py-2 px-3 w-1/2"
                            v-text="trans('log.attributes.editor')"
                        />
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="log in logs.data"
                        :key="log.id"
                        class="border-2 border-white border-solid"
                    >
                        <td class="py-2 px-3">
                            <time
                                :datetime="log.created_at"
                                v-text="longDatetime(log.created_at)"
                            />
                        </td>
                        <td
                            class="py-2 px-3"
                            v-text="log.user.name"
                        />
                    </tr>
                </tbody>
            </table>

            <div
                v-else
                v-text="trans('log.attributes.no-data')"
            />
        </PageCard>

        <InertiaPagination :pagination="logs.pagination" />
    </div>
</template>

<script>
import PageCard from '@/components/page/PageCard.vue';
import EntityIcon from '@/components/EntityIcon.vue';
import { longDatetime } from '@/helpers/datetime.js';
import InertiaPagination from '@/components/InertiaPagination.vue';

export default {
    components: { PageCard, EntityIcon, InertiaPagination },
    props: {
        tool: {
            type: Object,
            required: true,
        },
        logs: {
            type: Object,
            required: true,
        },
    },
    methods: { longDatetime },
};
</script>
