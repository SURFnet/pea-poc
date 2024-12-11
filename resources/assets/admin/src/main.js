import '@/bootstrap';

import Vue from 'vue';
import { createInertiaApp } from '@inertiajs/vue2';
import '@/icons';

import { createPinia, PiniaVuePlugin } from 'pinia';

const inertiaElement = document.getElementById('app');

if (inertiaElement) {
    createInertiaApp({
        progress: {
            color: '#1a56db',
        },
        id: 'app',
        /* eslint-disable-next-line */
        resolve: (name) => require(`./pages/${name}Page.vue`),
        /**
         * Sets up Inertia.
         *
         * @param {object} setup
         * @param {Element} setup.el
         * @param {object} setup.App
         * @param {object} setup.props
         * @param {object} setup.plugin
         */
        setup({ el, App, props, plugin }) {
            Vue.use(plugin);
            Vue.use(PiniaVuePlugin);
            const pinia = createPinia();

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
                pinia,
                render: (h) => h(App, props),
            }).$mount(el);
        },
    });
}
