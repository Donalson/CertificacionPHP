require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('usuarios', require('./components/UsuariosComponent.vue').default);
Vue.component('comidas', require('./components/ComidasComponent.vue').default);
Vue.component('bebidas', require('./components/BebidasComponent.vue').default);

const app = new Vue({
    el: '#app',
});
