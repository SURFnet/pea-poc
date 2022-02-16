/**
 * Format a decimal number to a Dutch formatted string (e.g. 123,45)
 *
 * @param {number} value
 *
 * @returns {string}
 */
export function decimal(value) {
    if (Number.isNaN(value) || value === Infinity || value === null) {
        return '';
    }

    const formatter = new Intl.NumberFormat('nl-NL', {
        style: 'decimal',
        minimumFractionDigits: 2,
    });

    return formatter.format(value);
}
