<template>
    <footer class="w-full | flex flex-row justify-end | bg-app border-t border-gray-300 | space-x-2 | p-4">
        <p class="flex-shrink-0 | text-gray-700" v-text="trans('footer.powered-by')" />
        <div class="h-10 w-20">
            <BrandLogo />
        </div>
    </footer>
</template>

<script>
import BrandLogo from '@/components/BrandLogo';

export default {
    components: {
        BrandLogo,
    },
};
</script>
