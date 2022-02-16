/**
 * Format a number to a Dutch formatted currency string (e.g. € 100,00)
 *
 * @param {number} value
 *
 * @returns {string}
 */
export function euro(value) {
    if (Number.isNaN(value) || value === Infinity || value === null) {
        return '';
    }

    const formatter = new Intl.NumberFormat('nl-NL', {
        style: 'currency',
        currency: 'EUR',
    });

    return formatter.format(value);
}

/**
 * Display a currency for humans.
 *
 * @param {number} value
 *
 * @returns {string}
 */
export function displayEuro(value) {
    const formattedValue = euro(value);

    if (!formattedValue) {
        return '€ —';
    }

    return formattedValue;
}
