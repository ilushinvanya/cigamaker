import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'

import VueResource from 'vue-resource';
Vue.use(VueResource);

Vue.http.options.root = process.env.NODE_ENV == 'production' ? '/' : 'http://cigamaker.ru';

Vue.config.productionTip = false;
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
