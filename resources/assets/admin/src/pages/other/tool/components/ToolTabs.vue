<template>
    <div class="bg-gray-100">
        <div class="container">
            <div class="sm:hidden">
                <label
                    for="tabs"
                    class="sr-only"
                    v-text="trans('page.select-tab')"
                />

                <select
                    class="block w-full | focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                    @change="selectTab($event.target.value)"
                >
                    <option
                        v-for="tab in tabs"
                        :key="tab"
                        :selected="tab === activeTab"
                        :value="tab"
                        v-text="trans(`page.other.tool.show.tabs.${tab}`)"
                    />
                </select>
            </div>

            <div class="hidden sm:block">
                <nav
                    class="relative flex | z-0"
                    :aria-label="trans('aria.tabs')"
                >
                    <a
                        v-for="tab in tabs"
                        :key="tab"
                        href="#"
                        :class="[
                            tab === activeTab
                                ? 'bg-white | text-blue-500'
                                : 'text-black hover:text-gray-700 | bg-gray-300 hover:bg-white',
                            'group relative | border-l border-r border-gray-300 | text-sm font-medium text-center hover:no-underline | py-4 px-4 mr-2 | focus:z-10 overflow-hidden',
                        ]"
                        :aria-current="tab === activeTab ? 'page' : undefined"
                        @click.prevent="selectTab(tab)"
                    >
                        <span v-text="trans(`page.other.tool.show.tabs.${tab}`)" />

                        <span
                            aria-hidden="true"
                            :class="[
                                tab === activeTab ? 'bg-blue-500' : 'bg-gray-300',
                                'absolute inset-x-0 top-0 h-0.5',
                            ]"
                        />
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        activeTab: {
            type: String,
            required: true,
        },
    },
    computed: {
        /**
         * Build the visible tabs
         *
         * @returns {Array}
         */
        tabs() {
            return ['product', 'education', 'support', 'technical', 'privacy_and_security'];
        },
    },
    methods: {
        /**
         * Handles selecting a tab.
         *
         * @param {string} tab
         */
        selectTab(tab) {
            this.$emit('tab-selected', tab);
        },
    },
};
</script>
