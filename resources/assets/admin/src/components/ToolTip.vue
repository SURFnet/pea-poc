<template>
    <span
        :data-text="text"
        class="tooltip"
    >
        <FontAwesomeIcon
            icon="info-circle"
            class="opacity-40 | ml-1"
        />
    </span>
</template>

<script>
export default {
    name: 'ToolTip',
    props: {
        text: {
            type: String,
            required: true,
        },
    },
};
</script>

<style scoped>
.tooltip {
    position: relative;
}

.tooltip::before {
    display: none;
    position: absolute;
    content: attr(data-text);
    z-index: 10;
    margin-top: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    background: #f4f6f8;
    padding: 0.625rem;
    width: 80vw;
    text-align: center;
    color: #2e2e2e;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

@media screen and (min-width: 768px) {
    .tooltip::before {
        top: 0;
        left: 100%;
        margin-left: 0.875rem;
        transform: translateY(-50%);
        width: 12rem;
    }
}

.tooltip:hover::before,
.tooltip:active::before {
    display: block;
}
</style>
