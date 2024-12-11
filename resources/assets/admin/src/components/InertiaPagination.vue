<template>
    <div class="mx-auto flex items-center justify-center">
        <nav
            v-if="pagination.has_pages"
            class="inline-flex justify-between | space-x-2"
        >
            <InertiaLink
                :href="pagination.first_page_url || '#'"
                class="pagination-item | text-lg"
                :preserve-scroll="preserveScroll"
                :class="{ disabled: disabledFirstPage }"
            >
                «
            </InertiaLink>

            <InertiaLink
                :href="pagination.previous_page_url || '#'"
                class="pagination-item | text-lg"
                :preserve-scroll="preserveScroll"
                :class="{ disabled: disabledPreviousPage }"
            >
                ‹
            </InertiaLink>

            <InertiaLink
                v-for="(url, index) in pages"
                :key="index"
                :href="url"
                class="pagination-item"
                :preserve-scroll="preserveScroll"
                :class="{ 'is-active': pagination.current_page == currentPage(index) }"
            >
                {{ currentPage(index) }}
            </InertiaLink>

            <InertiaLink
                :href="pagination.next_page_url || '#'"
                class="pagination-item | text-lg"
                :preserve-scroll="preserveScroll"
                :class="{ disabled: disabledNextPage }"
            >
                ›
            </InertiaLink>

            <InertiaLink
                :href="pagination.last_page_url || '#'"
                class="pagination-item | text-lg"
                :preserve-scroll="preserveScroll"
                :class="{ disabled: disabledLastPage }"
            >
                »
            </InertiaLink>
        </nav>
    </div>
</template>

<script>
export default {
    props: {
        pagination: {
            type: Object,
            required: true,
        },
        preserveScroll: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        /**
         * Get closest pages from the current page for a short pagination list.
         *
         * @returns {Array}
         */
        pages() {
            // The index in the page_urls array.
            const index = this.pagination.current_page - 1;

            const currentPage = this.pagination.page_urls.slice(index, index + 1);
            const previousPage = this.pagination.page_urls.slice(index - 1, index);
            const nextPages = this.pagination.page_urls.slice(index + 1, index + 3);

            //  If it's the first page we dont have a previous page
            if (this.pagination.current_page === 1) {
                return [...currentPage, ...nextPages];
            }

            return [...previousPage, ...currentPage, ...nextPages];
        },
        /**
         * Determines if the first page link should be disabled.
         *
         * @returns {boolean}
         */
        disabledFirstPage() {
            return this.pagination.current_page === 1;
        },
        /**
         * Determines if the previous page link should be disabled.
         *
         * @returns {boolean}
         */
        disabledPreviousPage() {
            return this.pagination.current_page === 1;
        },
        /**
         * Determines if the next page link should be disabled.
         *
         * @returns {boolean}
         */
        disabledNextPage() {
            return this.pagination.has_more_pages === false;
        },
        /**
         * Determines if the last page link should be disabled.
         *
         * @returns {boolean}
         */
        disabledLastPage() {
            return this.pagination.has_more_pages === false;
        },
    },
    methods: {
        /**
         * Get the current page number.
         *
         * @param {number} index
         *
         * @returns {number}
         */
        currentPage(index) {
            if (this.pagination.current_page === 1) {
                return 1 + index;
            }

            // Starts from the previous page.
            return this.pagination.current_page - 1 + index;
        },
    },
};
</script>
