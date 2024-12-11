import Vue from 'vue';

import Meta from 'vue-meta';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import PortalVue from 'portal-vue';

import * as Sentry from '@sentry/browser';
import { Vue as VueIntegration, RewriteFrames } from '@sentry/integrations';
import { Link, router } from '@inertiajs/vue2';

import { date } from '@/helpers/datetime';
import { displayEuro } from '@/helpers/currency';
import { displayBoolean } from '@/helpers/boolean';
import { decimal } from '@/helpers/number';

/**
 * Initialize plugins
 */
Vue.use(Meta);
Vue.use(PortalVue);

/**
 * A wrapper around the trans() function, so we can be consistent between the front-end and the back-end code.
 *
 * @param {string} string
 * @param {object} args
 *
 * @returns {string}
 */
window.trans = (string, args) => {
    if (typeof Lang === 'undefined') {
        return string;
    }

    return Lang.get(string, args);
};

/**
 * A wrapper around the Lang.choice() function, so we can be consistent between the front-end and the back-end code.
 *
 * @param {string} string
 * @param {number} amount
 * @param {object} args
 *
 * @returns {string}
 */
window.trans_choice = (string, amount, args) => {
    if (typeof Lang === 'undefined') {
        return string;
    }

    return Lang.choice(string, amount, args);
};

/**
 * Bind the global helper functions (trans, route) to Vue for use inside the template parts.
 * trans(): uses Lang.js
 * route(): uses Ziggy
 */
/* eslint-disable camelcase */
Vue.prototype.trans = trans;
Vue.prototype.trans_choice = trans_choice;
Vue.prototype.route = (string, ...args) => {
    if (typeof route === 'undefined') {
        return string;
    }

    return route(string, ...args);
};
/* eslint-enable camelcase */

/**
 * Initialize global components.
 */
Vue.component('FontAwesomeIcon', FontAwesomeIcon);
Vue.component('InertiaLink', Link);

/**
 * Initialize Vue filters.
 */
Vue.filter('date', date);
Vue.filter('displayEuro', displayEuro);
Vue.filter('displayBoolean', displayBoolean);
Vue.filter('decimal', decimal);

Vue.config.productionTip = false;

/**
 * Initialize Sentry for error logging.
 */
if (process.env.VUE_APP_SENTRY_LOG_ERRORS) {
    Sentry.init({
        dsn: process.env.VUE_APP_SENTRY_DSN,
        environment: process.env.VUE_APP_SENTRY_ENVIRONMENT,
        integrations: [new VueIntegration(Vue), new RewriteFrames()],
    });
}

router.on('navigate', () => {
    // eslint-disable-next-line no-undef
    dataLayer.push(['trackPageView']);
});
