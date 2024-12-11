<template>
    <div class="app | flex flex-col min-h-screen | bg-app">
        <NotificationContainer />

        <header>
            <MainMenu
                :locale="$page.props.app.locale"
                :active-locales="$page.props.app.activeLocales"
            />
        </header>

        <main class="flex-grow">
            <main class="max-w-7xl mx-auto bg-white | pb-10 lg:pb-12 lg:px-8">
                <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                    <FilterSideBar />

                    <div class="sm:px-6 lg:px-0 lg:col-span-9">
                        <slot :current-user="$page.props.currentUser" />
                    </div>
                </div>
            </main>
        </main>

        <PageFooter />
    </div>
</template>

<script>
import NotificationContainer from '@/layouts/components/NotificationContainer';
import MainMenu from '@/layouts/components/MainMenu';
import PageFooter from '@/layouts/components/PageFooter';
import FilterSideBar from '@/layouts/components/FilterSideBar';

export default {
    components: {
        MainMenu,
        NotificationContainer,
        PageFooter,
        FilterSideBar,
    },
};
</script>
