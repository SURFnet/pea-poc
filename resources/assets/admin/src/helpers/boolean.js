/**
 * Format a boolean value, usually from a Laravel resource.
 *
 * @param {number} value
 *
 * @returns {string}
 */
export function displayBoolean(value) {
    if (value === null) {
        return '—';
    }

    if (Boolean(value) === false) {
        return trans('general.boolean.options.no');
    }

    return trans('general.boolean.options.yes');
}
