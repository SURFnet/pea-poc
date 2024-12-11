<template>
    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
        <TagPill
            v-for="item in itemSet(itemList)"
            :key="item.id"
        >
            {{ item.name }}
        </TagPill>

        <TagPill
            v-if="remainingItems(itemList)"
            class="cursor-pointer"
            @click.native="toggleShownItems"
        >
            {{ remainingItems(itemList) }}
        </TagPill>
    </div>
</template>

<script>
import take from 'lodash/take';
import TagPill from '@/components/TagPill';

const numberOfTags = 2;

export default {
    components: {
        TagPill,
    },
    props: {
        itemList: {
            type: Array,
            required: true,
        },
    },
    /**
     * Holds the data
     *
     * @returns {object}
     */
    data() {
        return {
            expanded: false,
        };
    },
    methods: {
        /**
         * Takes and shows only the first two items in the Array. If expanded, shows all.
         *
         * @param {Array} items
         *
         * @returns {Array}
         */
        itemSet(items) {
            if (this.expanded === false) {
                return take(items, numberOfTags);
            }

            return items;
        },
        /**
         * If there are more than 2 items, calculates and shows the result. If the list of items is expanded, shows "-" instead.
         *
         * @param {Array} items
         *
         * @returns {number}
         */
        remainingItems(items) {
            const remainder = items.length - numberOfTags;
            if (remainder <= 0) {
                return 0;
            }
            if (this.expanded === true) {
                return '-';
            }

            return `+${remainder}`;
        },
        /**
         * Expands or reduces the amount of shown items in the targeted row, depending on the expanded state
         */
        toggleShownItems() {
            this.expanded = !this.expanded;
        },
    },
};
</script>
