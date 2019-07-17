/**
 * Модифицированный плагин
 * @link https://github.com/s4l1h/vue-toastr
 */

import vueNotifications from './vue-toastr.js';
import './vue-toastr.scss';

vueNotifications.install = function (Vue) {
    let MyComponent = Vue.extend({
        template: '<vue-notifications ref="vueToastr"></vue-notifications>',
        components: {'vue-notifications': vueNotifications}
    });

    let component = new MyComponent().$mount();

    document.body.appendChild(component.$el);

    Vue.prototype.$notifications = component.$refs.vueToastr
};

if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(vueNotifications)
}

export default vueNotifications;
