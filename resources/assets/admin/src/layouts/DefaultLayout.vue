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
            <slot :current-user="$page.props.currentUser" />
        </main>

        <PageFooter />
    </div>
</template>

<script>
import NotificationContainer from '@/layouts/components/NotificationContainer';
import MainMenu from '@/layouts/components/MainMenu';
import PageFooter from '@/layouts/components/PageFooter';

export default {
    components: {
        MainMenu,
        NotificationContainer,
        PageFooter,
    },
};
</script>
