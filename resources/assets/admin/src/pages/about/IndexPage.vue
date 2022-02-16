<template>
    <div class="bg-white">
        <div class="container">
            <BannerHeader
                :page="'about'"
                :title="trans('page.about.index.section-header.title')"
                :subtitle="
                    trans('page.about.index.section-header.subtitle', {
                        institute: $page.props.currentUser.institute.full_name,
                    })
                "
                :image="'/dist/admin/images/about/header-bg.jpg'"
                :show-search="false"
            />

            <div class="text-black leading-7">
                <section class="pb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 | w-11/12 mx-auto">
                        <div
                            class="thumbnail-container | w-full h-64 lg:h-1/2 | bg-cover bg-center bg-no-repeat"
                            style="background-image: url('/dist/admin/images/about/pc.jpg')"
                        />
                        <div>
                            <h3 class="text-xl text-black font-bold mb-3">
                                {{ trans('page.about.index.sections.how-to-use.title') }}
                            </h3>
                            <p class="mb-2">
                                {{ trans('page.about.index.sections.how-to-use.text.0') }}
                            </p>
                            <p>{{ trans('page.about.index.sections.how-to-use.text.1') }}</p>
                        </div>
                    </div>
                </section>

                <section class="bg-app py-8">
                    <div class="w-11/12 mx-auto">
                        <h3 class="text-xl text-black font-bold mb-3">
                            {{ trans('page.about.index.sections.pea-support.title') }}
                        </h3>
                        <p>{{ trans('page.about.index.sections.pea-support.text') }}</p>
                    </div>
                </section>

                <section class="py-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 | w-11/12 mx-auto">
                        <div>
                            <h3 class="text-xl text-black font-bold mb-3">
                                {{ trans('page.about.index.sections.about-edutools-project-and-surf-title.title') }}
                            </h3>
                            <p>{{ trans('page.about.index.sections.about-edutools-project-and-surf-title.text') }}</p>
                        </div>
                        <div
                            class="thumbnail-container | w-full h-64 lg:h-1/2 | bg-cover bg-center bg-no-repeat"
                            style="background-image: url('/dist/admin/images/about/aula.jpg')"
                        />
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/layouts/DefaultLayout';

import BannerHeader from '@/components/page/BannerHeader';

export default {
    components: {
        BannerHeader,
    },
    layout: Layout,
    /**
     * The reactive metainfo object.
     *
     * @returns {object}
     */
    metaInfo() {
        return {
            title: trans('page.about.index.title'),
        };
    },
};
</script>

<style scoped>
.thumbnail-container {
    min-height: 250px;
}
</style>
