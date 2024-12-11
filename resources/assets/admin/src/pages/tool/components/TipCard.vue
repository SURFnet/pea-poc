<template>
    <div
        class="w-full md:w-2/3 flex flex-row | bg-white border border-gray-200 shadow-lg | text-black rounded-md hover:border-gray-300 | text-left | transition-all | p-6 space-x-4 | group"
    >
        <div>
            <div class="flex flex-row items-center space-x-2">
                <div class="w-6 h-6 | flex justify-center items-center rounded-full bg-yellow-500">
                    <FontAwesomeIcon
                        icon="bullhorn"
                        class="text-white text-sm"
                    />
                </div>

                <p
                    class="text-sm uppercase"
                    v-text="trans('page.tool.tip.tip')"
                />
            </div>
        </div>

        <div class="space-y-4">
            <p
                class="text-black font-bold"
                v-text="trans('page.tool.tip.title')"
            />

            <p v-text="trans('page.tool.tip.text')" />
        </div>
    </div>
</template>
