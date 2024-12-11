<template>
    <footer class="flex justify-center | w-full | bg-gray-200">
        <div class="flex flex-col items-center px-5 py-2 md:px-8 md:py-4 w-full max-w-7xl">
            <div class="flex justify-between w-full">
                <ul class="font-semibold list-none pl-0">
                    <li
                        v-for="(link, index) in links"
                        :key="index"
                    >
                        <a
                            :href="link.url"
                            target="_blank"
                            class="text-sm text-blue-500"
                            v-text="link.text"
                        />
                    </li>
                </ul>

                <div class="m-2">
                    <BrandLogo class="h-8 lg:h-10" />
                </div>
            </div>

            <span
                class="mb-2 mt-7 lg:mt-8 text-center text-xs"
                v-text="trans('footer.disclaimer')"
            />
        </div>
    </footer>
</template>

<script>
import BrandLogo from '@/components/BrandLogo';

export default {
    components: {
        BrandLogo,
    },

    /**
     * @returns {object} data of the links visible in footer
     */
    data() {
        return {
            links: [
                {
                    url: trans('footer.links.link1.url'),
                    text: trans('footer.links.link1.text'),
                },
                {
                    url: trans('footer.links.link2.url'),
                    text: trans('footer.links.link2.text'),
                },
                {
                    url: trans('footer.links.link3.url'),
                    text: trans('footer.links.link3.text'),
                },
                {
                    url: trans('footer.links.link4.url'),
                    text: trans('footer.links.link4.text'),
                },
            ],
        };
    },
};
</script>
