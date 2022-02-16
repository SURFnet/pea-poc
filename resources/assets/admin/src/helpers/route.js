/**
 * Check if we should mark the nav menu item as active.
 * Based on the current function from Ziggy: https://github.com/tightenco/ziggy/blob/master/src/js/route.js#L179.
 *
 * @param {string|Array} routeName
 * @param {string} currentRoute When used in the template in a SFC the Vue component the shared Inertia $page.props data is available.
 *
 * @returns {boolean}
 */
export function isActiveRoute(routeName, currentRoute = this.$page.props.currentRouteName) {
    const testRouteName = (testSource, testTarget) => {
        const pattern = new RegExp(`^${testSource.replace('*', '.*').replace('.', '.')}$`, 'i');

        return pattern.test(testTarget);
    };

    if (!Array.isArray(routeName)) {
        return testRouteName(routeName, currentRoute);
    }

    return routeName.some((routeNameItem) => testRouteName(routeNameItem, currentRoute));
}

/**
 * Get the current URL without parameters.
 *
 * @returns {string}
 */
export function getCurrentUrl() {
    return `${document.location.protocol}//${document.location.hostname}${document.location.pathname}`;
}
