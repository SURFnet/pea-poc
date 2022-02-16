import '@/bootstrap';

import Vue from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue';
import '@/icons';

const inertiaElement = document.getElementById('app');

if (inertiaElement) {
    createInertiaApp({
        id: 'app',
        /* eslint-disable-next-line */
        resolve: (name) => require(`./pages/${name}Page.vue`),

        /**
         * Sets up Inertia.
         *
         * @param {object} setup
         * @param {Element} setup.el
         * @param {object} setup.app
         * @param {object} setup.props
         */
        setup({ el, app, props }) {
            new Vue({
                /**
                 * The reactive metainfo object.
                 *
                 * @returns {object}
                 */
                metaInfo() {
                    return {
                        title: 'Loading...',
                        titleTemplate: (chunck) => {
                            if (!this.$page.props) {
                                return chunck;
                            }

                            return `${chunck} - ${this.$page.props.app.name}`;
                        },
                    };
                },
                render: (h) => h(app, props),
            }).$mount(el);
        },
    });
}
