<template>
    <div class="flex flew-row flex-wrap items-center">
        <TagPill v-for="item in itemSet(itemList)" :key="item.id" class="mr-4">
            {{ item.name }}
        </TagPill>
        <div class="cursor-pointer" @click="toggleShownItems">
            <TagPill v-if="remainingItems(itemList)">
                {{ remainingItems(itemList) }}
            </TagPill>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
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
                return _.take(items, numberOfTags);
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
