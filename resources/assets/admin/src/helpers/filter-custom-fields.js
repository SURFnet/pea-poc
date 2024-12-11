/**
 * Return a filtered array with the custom fields for a tab type
 *
 * @param {Array} filterArray
 * @param {string} tabType
 *
 * @returns {Array}
 */
export function filterCustomFields(filterArray, tabType) {
    return filterArray.filter((item) => item.tab_type === tabType);
}
