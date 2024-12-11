<template>
    <div class="app | flex flex-col min-h-screen | bg-app">
        <NotificationContainer />

        <header>
            <MainMenu
                :locale="$page.props.app.locale"
                :active-locales="$page.props.app.activeLocales"
            />
        </header>

        <main class="lg:flex lg:flex-row flex-grow">
            <AdminSideBar class="lg:w-64 flex-shrink-0" />

            <slot :current-user="$page.props.currentUser" />
        </main>

        <PageFooter />
    </div>
</template>

<script>
import NotificationContainer from '@/layouts/components/NotificationContainer';
import MainMenu from '@/layouts/components/MainMenu';
import PageFooter from '@/layouts/components/PageFooter';
import AdminSideBar from '@/layouts/components/AdminSideBar';

export default {
    components: {
        MainMenu,
        NotificationContainer,
        PageFooter,
        AdminSideBar,
    },
};
</script>
