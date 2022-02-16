/**
 * Formats an array for use in the select input component.
 *
 * @param {Array} array
 *
 * @returns {object}
 */
export function selectFromArray(array) {
    const options = [];

    Object.keys(array).map((key) =>
        options.push({
            label: array[key],
            value: key,
        })
    );

    return options;
}
