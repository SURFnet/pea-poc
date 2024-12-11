/**
 * This file should contain all logic for building URL's for filtering tools to keep the code maintainable.
 */

/**
 * Returns the URL for filtering
 *
 * @param {string} searchTerm
 * @param {string} tagsAsString
 *
 * @returns {string}
 */
function getUrl(searchTerm, tagsAsString) {
    const searchQueryParams = searchTerm !== '' ? `?search=${searchTerm}` : '';

    return `${route('tool.index', { tags: tagsAsString })}${searchQueryParams}`;
}

/**
 * Returns tags of specific type as string for URL
 *
 * @param {string} type
 * @param {Array} slugs
 *
 * @returns {string}
 */
function getTagsAsString(type, slugs) {
    return `${type}:${slugs.join(',')}`;
}

/**
 * Generates the tags part for the filter URL
 *
 * @param {object} tagTypesWithSlugs
 *
 * @returns {string}
 */
function getTagTypesWithSlugsAsString(tagTypesWithSlugs) {
    const parts = [];

    Object.keys(tagTypesWithSlugs).forEach((type) => {
        const slugs = tagTypesWithSlugs[type];

        if (slugs.length > 0) {
            parts.push(getTagsAsString(type, slugs));
        }
    });

    return parts.join('/');
}

/**
 * Returns the URL for filtering by filter values
 *
 * @param {string} searchTerm
 * @param {object} tagTypesWithSlugs
 *
 * @returns {string}
 */
export function getFilterUrlByFilters(searchTerm, tagTypesWithSlugs) {
    return getUrl(searchTerm, getTagTypesWithSlugsAsString(tagTypesWithSlugs));
}

/**
 * Returns the URL for filtering by a specific tag
 *
 * @param {object} tag
 *
 * @returns {string}
 */
export function getFilterUrlByTag(tag) {
    return getUrl('', getTagsAsString(tag.type, [tag.slug]));
}
