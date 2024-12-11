import { defineStore } from 'pinia';

export const useToolFilterStore = defineStore('toolFilter', {
    state: () => ({
        searchTerm: '',
        tagTypesWithSlugs: {},
    }),
    actions: {
        /**
         * Resets all filters
         */
        reset() {
            this.setSearchTerm('');
            this.setTagTypesWithSlugs({});
        },
        /**
         * Sets the search term
         *
         * @param {string} searchTerm
         */
        setSearchTerm(searchTerm) {
            this.searchTerm = searchTerm;
        },
        /**
         * Sets all tag types with slugs
         *
         * @param {object} tagTypesWithSlugs
         */
        setTagTypesWithSlugs(tagTypesWithSlugs) {
            this.tagTypesWithSlugs = tagTypesWithSlugs;
        },
        /**
         * Sets tags grouped by type
         *
         * @param {string} type
         * @param {Array} slugs
         */
        updateTagTypesWithSlugs(type, slugs) {
            if (slugs.length < 1) {
                delete this.tagTypesWithSlugs[type];
            } else {
                this.tagTypesWithSlugs[type] = slugs;
            }
        },
    },
});
