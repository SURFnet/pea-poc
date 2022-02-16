<template>
    <div
        v-if="$page.props.flashNotifications && $page.props.flashNotifications.length > 0"
        class="relative | z-50 | print:hidden"
    >
        <Notification
            v-for="(notification, fieldIndex) in $page.props.flashNotifications"
            :key="fieldIndex"
            :message="notification.message"
            :level="notification.level"
        />
    </div>
</template>

<script>
import Notification from '@/components/Notification';

export default {
    components: {
        Notification,
    },
};
</script>
